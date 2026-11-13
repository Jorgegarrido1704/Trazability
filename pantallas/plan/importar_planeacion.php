<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require __DIR__ . '/conexion.php';

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

if ($argc !== 2) {
    fwrite(STDERR, "Uso: php importar_planeacion.php archivo.csv\n");
    exit(1);
}

$archivo = @fopen($argv[1], 'rb');
if ($archivo === false) {
    fwrite(STDERR, "No se pudo abrir el archivo CSV.\n");
    exit(1);
}

$pdo = null;
$salida = 0;
try {
    $ahora = new DateTimeImmutable('now', zonaPlanta());
    $fechaCarga = $ahora->format('Y-m-d');
    $fechaSiguiente = $ahora->modify('+1 day')->format('Y-m-d');
    $primeraLinea = fgets($archivo);
    if ($primeraLinea === false) {
        throw new InvalidArgumentException('El CSV está vacío.');
    }
    $primeraLinea = preg_replace('/^\xEF\xBB\xBF/', '', $primeraLinea);
    $esperado = ['numero de parte', 'wo', 'cantidad', 'l', 'e', 'l', 'c', 'e', 'fecha requerida', 'comentarios'];
    $delimitador = null;
    foreach ([',', ';'] as $opcion) {
        $encabezados = array_map('normalizarEncabezado', str_getcsv(rtrim($primeraLinea, "\r\n"), $opcion, '"', ''));
        if (in_array($encabezados[9] ?? '', ['commentarios', 'commentario', 'comentario'], true)) {
            $encabezados[9] = 'comentarios';
        }
        if ($encabezados === $esperado) {
            $delimitador = $opcion;
            break;
        }
    }
    if ($delimitador === null) {
        throw new InvalidArgumentException('Los encabezados no coinciden con la plantilla. Revisa las diez columnas y su orden.');
    }

    $pdo = conectar();
    $motor = $pdo->query(
        "SELECT e.TRANSACTIONS
         FROM information_schema.TABLES AS t
         JOIN information_schema.ENGINES AS e ON e.ENGINE = t.ENGINE
         WHERE t.TABLE_SCHEMA = DATABASE() AND t.TABLE_NAME = 'pantallas'"
    )->fetchColumn();
    if ($motor !== 'YES') {
        throw new RuntimeException('La tabla pantallas debe usar un motor transaccional, como InnoDB.');
    }

    // Serializa las cargas de Planeación, incluso si todavía no hay filas para hoy.
    // MySQL/MariaDB libera el bloqueo al cerrar la conexión de este proceso.
    $bloqueo = $pdo->query(
        "SELECT GET_LOCK(CONCAT('cvts.plan.', SHA1(DATABASE())), 30)"
    )->fetchColumn();
    if ((string) $bloqueo !== '1') {
        throw new RuntimeException('No se pudo obtener el bloqueo de Planeación. Vuelve a intentar la carga.');
    }

    $pdo->beginTransaction();
    $borrar = $pdo->prepare(
        "DELETE FROM pantallas
         WHERE pantalla = 'plan' AND fecha_up >= ? AND fecha_up < ?"
    );
    $borrar->execute([$fechaCarga, $fechaSiguiente]);
    $reemplazados = $borrar->rowCount();

    $guardar = $pdo->prepare(
        "INSERT INTO pantallas (pantalla, fecha_up, np, wo, qty_wo, corte, ensa, loom, cal, emb, fecha, comentarios)
         VALUES ('plan', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $registro = 1;
    $guardados = 0;

    while (($fila = fgetcsv($archivo, 0, $delimitador, '"', '')) !== false) {
        $registro++;
        if ($fila === [null]) {
            continue;
        }
        try {
            if (count($fila) !== 10) {
                throw new InvalidArgumentException('Se esperaban diez columnas.');
            }
            if (!preg_match('//u', implode('', $fila))) {
                throw new InvalidArgumentException('Guarda el CSV en UTF-8.');
            }

            [$np, $wo, $cantidad, $corte, $ensa, $loom, $cal, $emb, $fecha, $comentarios] = array_map('trim', $fila);
            if ($np === '' || $wo === '') {
                throw new InvalidArgumentException('Número de parte y WO son obligatorios.');
            }
            if (!preg_match('/\A[0-9]+\z/', $cantidad)) {
                throw new InvalidArgumentException('Cantidad debe ser un entero mayor o igual a cero.');
            }
            $guardar->execute([
                $fechaCarga, $np, $wo, $cantidad, $corte, $ensa, $loom, $cal, $emb,
                fechaCliente($fecha), $comentarios,
            ]);
            $guardados++;
        } catch (InvalidArgumentException $error) {
            throw new InvalidArgumentException("Registro {$registro}: " . $error->getMessage(), 0, $error);
        } catch (PDOException $error) {
            throw new RuntimeException("La base de datos rechazó el registro {$registro}: " . $error->getMessage(), 0, $error);
        }
    }

    if (!feof($archivo)) {
        throw new RuntimeException('Ocurrió un error al leer el CSV.');
    }
    if ($guardados === 0) {
        throw new InvalidArgumentException('El CSV no contiene registros para importar.');
    }
    $pdo->commit();
    fwrite(STDOUT, "Registros anteriores eliminados: {$reemplazados}. Registros nuevos: {$guardados}. Fecha de carga: {$fechaCarga}.\n");
} catch (Throwable $error) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    $salida = 1;
} finally {
    fclose($archivo);
}
exit($salida);
