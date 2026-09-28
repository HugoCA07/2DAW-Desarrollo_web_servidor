<?php

$asignaturas = ['DAWES', 'DIWE', 'DAWCLI', 'INGLPRO', 'IPE II', 'OPT', 'TFG', 'DEAWEB'];

foreach ($asignaturas as $clave => $valor) {
    echo '<pre>';
    print_r($clave . '-' . $valor);
    echo '</pre>';
}
