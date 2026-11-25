<?php
/**
 * Comparador BOM (.xlsx) vs Reporte (.xls)
 *
 * BOM  : Material = columna B, Qty = columna E, desde la fila 5 hasta que B esté vacía
 * XLS  : Part No  = columna E, Qty = columna H, desde la fila 2 hasta que E esté vacía
 *
 * Requiere:  composer require phpoffice/phpspreadsheet
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);
require   '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// ---------- Configuración ----------
const BOM_COL_PART = 'B';
const BOM_COL_QTY  = 'E';
const BOM_START    = 5;

const XLS_COL_PART = 'E';
const XLS_COL_QTY  = 'H';
const XLS_START    = 2;

// El BOM trae decimales largos (1.8864...) y el XLS viene redondeado a 2 (1.89).
// Se comparan ambos redondeados a este número de decimales.
const QTY_DECIMALS = 2;

// ---------- Funciones ----------
function normalizar(?string $v): string
{
    return strtoupper(trim((string)$v));
}

/**
 * Lee un archivo y devuelve [ 'PARTNUM' => cantidad_total ].
 * Si un número de parte aparece varias veces, suma las cantidades.
 */
function leerArchivo(string $ruta, string $colPart, string $colQty, int $filaInicio): array
{
    $reader = IOFactory::createReaderForFile($ruta);
    $reader->setReadDataOnly(true);
    $hoja = $reader->load($ruta)->getSheet(0); // primera hoja

    $datos = [];
    for ($fila = $filaInicio; ; $fila++) {
        $celdaPart = $hoja->getCell($colPart . $fila)->getCalculatedValue();
        $part = normalizar(is_scalar($celdaPart) ? (string)$celdaPart : '');
        if ($part === '') {
            break; // celda vacía → fin
        }
        $q = $hoja->getCell($colQty . $fila)->getCalculatedValue();
        $q = is_numeric($q) ? (float)$q : 0.0;

        $datos[$part] = ($datos[$part] ?? 0.0) + $q;
    }
    return $datos;
}

function guardarSubida(string $campo): ?string
{
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $ext = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['xls', 'xlsx'], true)) {
        return null;
    }
    $tmp = tempnam(sys_get_temp_dir(), 'cmp_') . '.' . $ext;
    move_uploaded_file($_FILES[$campo]['tmp_name'], $tmp);
    return $tmp;
}

function fmt(?float $n): string
{
    return $n === null ? '—' : rtrim(rtrim(number_format($n, 4, '.', ''), '0'), '.');
}

// ---------- Procesamiento ----------
$resultado = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rutaBom = guardarSubida('bom');
    $rutaXls = guardarSubida('xls');

    if (!$rutaBom || !$rutaXls) {
        $error = 'Sube ambos archivos (.xls o .xlsx).';
    } else {
        try {
            $bom = leerArchivo($rutaBom, BOM_COL_PART, BOM_COL_QTY, BOM_START);
            $xls = leerArchivo($rutaXls, XLS_COL_PART, XLS_COL_QTY, XLS_START);

            $todos = array_unique(array_merge(array_keys($bom), array_keys($xls)));
            sort($todos, SORT_NATURAL);

            $filas = [];
            $res = ['ok' => 0, 'qty' => 0, 'soloBom' => 0, 'soloXls' => 0];

            foreach ($todos as $p) {
                $qb = $bom[$p] ?? null;
                $qx = $xls[$p] ?? null;

                if ($qb !== null && $qx === null) {
                    $estado = 'Solo en BOM';
                    $res['soloBom']++;
                } elseif ($qb === null && $qx !== null) {
                    $estado = 'Solo en XLS';
                    $res['soloXls']++;
                } elseif (round($qb, QTY_DECIMALS) === round($qx, QTY_DECIMALS)) {
                    $estado = 'OK';
                    $res['ok']++;
                } else {
                    $estado = 'Cantidad diferente';
                    $res['qty']++;
                }
                $filas[] = ['part' => $p, 'bom' => $qb, 'xls' => $qx, 'estado' => $estado];
            }
            $resultado = ['filas' => $filas, 'res' => $res];
        } catch (Throwable $e) {
            $error = 'Error al leer los archivos: ' . $e->getMessage();
        }
    }
    foreach ([$rutaBom, $rutaXls] as $r) {
        if ($r && file_exists($r)) {
            @unlink($r);
        }
    }
}

$clases = [
    'OK' => 'ok',
    'Cantidad diferente' => 'qty',
    'Solo en BOM' => 'bom',
    'Solo en XLS' => 'xls',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Comparador BOM vs XLS</title>
<style>
  body{font-family:system-ui,Arial,sans-serif;max-width:900px;margin:30px auto;padding:0 16px;color:#222}
  h1{font-size:22px}
  form{display:flex;gap:16px;flex-wrap:wrap;align-items:flex-end;background:#f4f6f8;padding:16px;border-radius:8px}
  label{display:flex;flex-direction:column;font-size:14px;gap:4px}
  button{padding:9px 18px;border:0;border-radius:6px;background:#2563eb;color:#fff;cursor:pointer;font-size:14px}
  .error{background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:6px;margin-top:16px}
  .resumen{display:flex;gap:10px;flex-wrap:wrap;margin:20px 0}
  .chip{padding:8px 14px;border-radius:20px;font-size:14px;cursor:pointer;border:2px solid transparent}
  .chip.activo{border-color:#222}
  table{border-collapse:collapse;width:100%;font-size:14px}
  th,td{padding:7px 10px;border-bottom:1px solid #e5e7eb;text-align:left}
  td.n,th.n{text-align:right}
  th{background:#f4f6f8}
  .ok{background:#dcfce7}.qty{background:#fef3c7}.bom{background:#dbeafe}.xls{background:#fce7f3}
  tr.ok td,tr.qty td,tr.bom td,tr.xls td{background:inherit}
</style>
</head>
<body>
<h1>Comparador BOM vs XLS</h1>

<form method="post" enctype="multipart/form-data">
  <label>BOM (.xlsx) — Material B5↓, Qty E5↓
    <input type="file" name="bom" accept=".xls,.xlsx" required>
  </label>
  <label>Reporte (.xls) — Part No E2↓, Qty H2↓
    <input type="file" name="xls" accept=".xls,.xlsx" required>
  </label>
  <button type="submit">Comparar</button>
</form>

<?php if ($error): ?>
  <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($resultado): $r = $resultado['res']; ?>
  <div class="resumen">
    <span class="chip ok activo"  data-f="todos">Todos: <?= count($resultado['filas']) ?></span>
    <span class="chip ok"  data-f="OK">OK: <?= $r['ok'] ?></span>
    <span class="chip qty" data-f="Cantidad diferente">Cantidad diferente: <?= $r['qty'] ?></span>
    <span class="chip bom" data-f="Solo en BOM">Solo en BOM: <?= $r['soloBom'] ?></span>
    <span class="chip xls" data-f="Solo en XLS">Solo en XLS: <?= $r['soloXls'] ?></span>
  </div>

  <table id="tabla">
    <thead>
      <tr><th>Número de parte</th><th class="n">Qty BOM</th><th class="n">Qty XLS</th><th>Estado</th></tr>
    </thead>
    <tbody>
    <?php foreach ($resultado['filas'] as $f): ?>
      <tr class="<?= $clases[$f['estado']] ?>" data-estado="<?= htmlspecialchars($f['estado']) ?>">
        <td><?= htmlspecialchars($f['part']) ?></td>
        <td class="n"><?= fmt($f['bom']) ?></td>
        <td class="n"><?= fmt($f['xls']) ?></td>
        <td><?= htmlspecialchars($f['estado']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    document.querySelectorAll('.chip').forEach(chip => {
      chip.addEventListener('click', () => {
        document.querySelectorAll('.chip').forEach(c => c.classList.remove('activo'));
        chip.classList.add('activo');
        const f = chip.dataset.f;
        document.querySelectorAll('#tabla tbody tr').forEach(tr => {
          tr.style.display = (f === 'todos' || tr.dataset.estado === f) ? '' : 'none';
        });
      });
    });
  </script>
<?php endif; ?>
</body>
</html>
