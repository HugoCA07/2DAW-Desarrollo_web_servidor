<?php

include __DIR__ . '/functions/functions_index.php';

// Obtenemos los inputs para el filtrado
$inputs = getInputs();

// CORRECCIÓN: En lugar de "pintarTabla", ahora simplemente "obtenemos" el array de reservas.
$tabla_reservas = obtenerReservas($inputs);

// Cargamos la plantilla, que ahora se encargará de pintar la variable $tabla_reservas
include __DIR__ . '/templates/index.tpl.php';
