<?php
require "../../app/conection.php";


$query = "SELECT pn, dataFrom, dataTo FROM listascorte 
          WHERE (dataFrom REGEXP '^SPL|^spl|^Spl|^splice|^SPLICE|^Empalme') 
          OR (dataTo REGEXP '^SPL|^spl|^Spl|^splice|^SPLICE|^Empalme') 
          ORDER BY pn ASC";

$result = mysqli_query($con, $query);

$resumen = [];
$combinaciones_unicas = [];

if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $pn = $row['pn'];
        $from = $row['dataFrom'];
        $to = $row['dataTo'];
        $pattern = '/^(SPL|spl|Spl|splice|SPLICE|Empalme)/i';

        if (preg_match($pattern, $from)) {
            if (!isset($resumen[$pn][$from])) $resumen[$pn][$from] = ['f' => 0, 't' => 0];
            $resumen[$pn][$from]['f']++;
        }
        if (preg_match($pattern, $to)) {
            if (!isset($resumen[$pn][$to])) $resumen[$pn][$to] = ['f' => 0, 't' => 0];
            $resumen[$pn][$to]['t']++;
        }
    }
}

// Procesar las combinaciones únicas basadas en la regla de distribución
foreach ($resumen as $pn => $splices) {
    foreach ($splices as $nombre => $counts) {
        $f = $counts['f'];
        $t = $counts['t'];

        // Aplicar tu regla lógica
        if ($f > 0 && $t == 0) {
            $val = ceil($f / 2); $f = ($val < 1) ? 1 : $val; $t = $f;
        } elseif ($t > 0 && $f == 0) {
            $val = ceil($t / 2); $t = ($val < 1) ? 1 : $val; $f = $t;
        }

        // Guardar como string único (ejemplo "1:2")
        $string_comb = "$f:$t";
        if (!in_array($string_comb, $combinaciones_unicas)) {
            $combinaciones_unicas[] = $string_comb;
        }
    }
}
// Ordenar las combinaciones para que se vea mejor (1:1, 1:2, 1:3...)
sort($combinaciones_unicas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; padding: 20px; }
        .tabs { display: flex; margin-bottom: 20px; border-bottom: 2px solid #dee2e6; }
        .tab-button { 
            padding: 10px 20px; cursor: pointer; border: none; background: none;
            font-weight: bold; color: #6c757d; outline: none;
        }
        .tab-button.active { color: #007bff; border-bottom: 3px solid #007bff; }
        .tab-content { display: none; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .tab-content.active { display: block; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #343a40; color: white; padding: 12px; }
        td { border: 1px solid #dee2e6; padding: 10px; text-align: center; }
        
        .chip { 
            display: inline-block; padding: 8px 15px; margin: 5px; 
            background: #e9ecef; border-radius: 20px; font-weight: bold; color: #495057;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>

    <div class="tabs">
        <button class="tab-button active" onclick="openTab(event, 'tab1')">Resumen Detallado</button>
        <button class="tab-button" onclick="openTab(event, 'tab2')">Catálogo de Combinaciones</button>
    </div>

    <div id="tab1" class="tab-content active">
        <table>
            <thead>
                <tr>
                    <th>PN</th>
                    <th>Splice</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumen as $pn => $splices): ?>
                    <?php foreach ($splices as $nombre => $counts): 
                        $f = $counts['f']; $t = $counts['t'];
                        if ($f > 0 && $t == 0) { $v = ceil($f/2); $f=$v; $t=$v; }
                        elseif ($t > 0 && $f == 0) { $v = ceil($t/2); $t=$v; $f=$v; }
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($pn) ?></strong></td>
                        <td><?= htmlspecialchars($nombre) ?></td>
                        <td><?= "$f : $t" ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="tab2" class="tab-content">
        <h3>Tipos de combinaciones detectadas en el sistema:</h3>
        <p>Estas son las configuraciones únicas de entrada/salida encontradas:</p>
        <div style="margin-top: 20px;">
            <?php foreach ($combinaciones_unicas as $comb): ?>
                <span class="chip"><?= $comb ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, content, buttons;
            content = document.getElementsByClassName("tab-content");
            for (i = 0; i < content.length; i++) content[i].style.display = "none";
            buttons = document.getElementsByClassName("tab-button");
            for (i = 0; i < buttons.length; i++) buttons[i].classList.remove("active");
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>