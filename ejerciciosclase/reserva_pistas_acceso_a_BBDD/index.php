<?php

include __DIR__ . '/functions/functions_index.php';

// Obtenemos los inputs para el filtrado
$inputs = getInputs();

// Obtenemos el array de reservas pasandole los inputs de la URL
$tabla_reservas = obtenerReservas($inputs);

// Cargamos la plantilla, que ahora se encargará de pintar la variable $tabla_reservas
include __DIR__ . '/templates/index.tpl.php';
