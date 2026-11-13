<?php
declare(strict_types=1);

require_once __DIR__ . '/conexion.php';

class CargaPlaneacionException extends RuntimeException {}

function normalizarEncabezado(string $valor): string
{
    $valor = strtolower(trim(strtr($valor, ['ú' => 'u', 'Ú' => 'U'])));
    return preg_replace('/\s+/', ' ', $valor);
}

function fechaCliente(string $valor): string
{
    foreach (['Y-m-d', 'j/n/Y', 'j-n-Y'] as $formato) {
        $fecha = DateTimeImmutable::createFromFormat('!' . $formato, $valor, zonaPlanta());
        $errores = DateTimeImmutable::getLastErrors();
        if ($fecha && ($errores === false || (!$errores['warning_count'] && !$errores['error_count']))) {
            if ((int) $fecha->format('Y') >= 1000) {
                return $fecha->format('Y-m-d');
            }
        }
    }

    throw new InvalidArgumentException('Fecha requerida inválida. Usa AAAA-MM-DD o DD/MM/AAAA.');
}

function cargarPlaneacion(string $ruta): array
{
    $archivo = @fopen($ruta, 'rb');
    if ($archivo === false) {
        throw new InvalidArgumentException('No se pudo abrir el archivo CSV.');
    }

    $con = null;
    $transaccionActiva = false;
    $bloqueado = false;
    try {
        $ahora = new DateTimeImmutable('now', zonaPlanta());
        $fechaCarga = $ahora->format('Y-m-d');
        $fechaSiguiente = $ahora->modify('+1 day')->format('Y-m-d');
        $primeraLinea = fgets($archivo);
        if ($primeraLinea === false) {
            throw new InvalidArgumentException('El CSV está vacío.');
        }
        $primeraLinea = preg_replace('/^\xEF\xBB\xBF/', '', $primeraLinea);
        $esperado = ['customer', 'numero de parte', 'wo', 'cantidad', 'l', 'e', 'l', 'c', 'e', 'fecha requerida', 'comentarios'];
        $delimitador = null;
        foreach ([',', ';'] as $opcion) {
            $encabezados = array_map('normalizarEncabezado', str_getcsv(rtrim($primeraLinea, "\r\n"), $opcion, '"', ''));
            if (in_array($encabezados[10] ?? '', ['commentarios', 'commentario', 'comentario'], true)) {
                $encabezados[10] = 'comentarios';
            }
            if ($encabezados === $esperado) {
                $delimitador = $opcion;
                break;
            }
        }
        if ($delimitador === null) {
            throw new InvalidArgumentException('Los encabezados no coinciden con la plantilla. Revisa las once columnas y su orden.');
        }

        $con = conectarPlaneacion();
        $motor = $con->query(
            "SELECT e.TRANSACTIONS
             FROM information_schema.TABLES AS t
             JOIN information_schema.ENGINES AS e ON e.ENGINE = t.ENGINE
             WHERE t.TABLE_SCHEMA = DATABASE() AND t.TABLE_NAME = 'pantallas'"
        )->fetch_row()[0] ?? null;
        if ($motor !== 'YES') {
            throw new CargaPlaneacionException('La tabla pantallas debe usar un motor transaccional, como InnoDB.');
        }

        // Serializa las cargas de Planeación, incluso si todavía no hay filas para hoy.
        $bloqueo = $con->query(
            "SELECT GET_LOCK(CONCAT('cvts.plan.', SHA1(DATABASE())), 30)"
        )->fetch_row()[0] ?? null;
        if ((string) $bloqueo !== '1') {
            throw new CargaPlaneacionException('No se pudo obtener el bloqueo de Planeación. Vuelve a intentar la carga.');
        }

        $bloqueado = true;
        $con->begin_transaction();
        $transaccionActiva = true;
        $borrar = $con->prepare(
            "DELETE FROM pantallas
             WHERE pantalla = 'plan' AND fecha_up >= ? AND fecha_up < ?"
        );
        $borrar->execute([$fechaCarga, $fechaSiguiente]);
        $reemplazados = (int) $borrar->affected_rows;
        $borrar->close();

        $guardar = $con->prepare(
            "INSERT INTO pantallas (pantalla, fecha_up, customer, np, wo, qty_wo, corte, ensa, loom, cal, emb, fecha, comentarios)
             VALUES ('plan', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $registro = 1;
        $guardados = 0;

        while (($fila = fgetcsv($archivo, 0, $delimitador, '"', '')) !== false) {
            $registro++;
            if ($fila === [null]) {
                continue;
            }
            try {
                if (count($fila) !== 11) {
                    throw new InvalidArgumentException('Se esperaban once columnas.');
                }
                if (!preg_match('//u', implode('', $fila))) {
                    throw new InvalidArgumentException('Guarda el CSV en UTF-8.');
                }

                [$customer, $np, $wo, $cantidad, $corte, $ensa, $loom, $cal, $emb, $fecha, $comentarios] = array_map('trim', $fila);
                if ($customer === '' || $np === '' || $wo === '') {
                    throw new InvalidArgumentException('Customer, número de parte y WO son obligatorios.');
                }
                if (preg_match('/\A.{1,255}\z/us', $customer) !== 1) {
                    throw new InvalidArgumentException('Customer admite hasta 255 caracteres.');
                }
                if (!preg_match('/\A[0-9]+\z/', $cantidad)) {
                    throw new InvalidArgumentException('Cantidad debe ser un entero mayor o igual a cero.');
                }
                $guardar->execute([
                    $fechaCarga, $customer, $np, $wo, $cantidad, $corte, $ensa, $loom, $cal, $emb,
                    fechaCliente($fecha), $comentarios,
                ]);
                $guardados++;
            } catch (InvalidArgumentException $error) {
                throw new InvalidArgumentException("Registro {$registro}: " . $error->getMessage(), 0, $error);
            } catch (mysqli_sql_exception $error) {
                throw new CargaPlaneacionException("La base de datos rechazó el registro {$registro}.", 0, $error);
            }
        }

        if (!feof($archivo)) {
            throw new RuntimeException('Ocurrió un error al leer el CSV.');
        }
        if ($guardados === 0) {
            throw new InvalidArgumentException('El CSV no contiene registros para importar.');
        }
        $guardar->close();
        $con->commit();
        $transaccionActiva = false;

        return [
            'fecha_carga' => $fechaCarga,
            'reemplazados' => $reemplazados,
            'guardados' => $guardados,
        ];
    } catch (Throwable $error) {
        if ($transaccionActiva) {
            try {
                $con->rollback();
            } catch (Throwable $errorRollback) {
                error_log('Planeación CVTS, reversión: ' . $errorRollback->getMessage());
            }
        }
        throw $error;
    } finally {
        fclose($archivo);
        if ($bloqueado) {
            try {
                $con->query("SELECT RELEASE_LOCK(CONCAT('cvts.plan.', SHA1(DATABASE())))")->free();
            } catch (Throwable $error) {
                error_log('Planeación CVTS, liberar bloqueo: ' . $error->getMessage());
            }
        }
    }
}
