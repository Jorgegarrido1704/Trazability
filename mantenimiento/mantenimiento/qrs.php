<?php
$datosMaquina = isset($_GET['datosMaquina']) ? $_GET['datosMaquina'] : 'No data provided';
echo "Datos de la máquina recibidos: " . htmlspecialchars($datosMaquina);