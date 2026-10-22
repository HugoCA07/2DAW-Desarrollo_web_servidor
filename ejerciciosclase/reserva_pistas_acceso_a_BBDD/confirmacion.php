<?php

include __DIR__ . '/functions/functions_confirmacion.php';

// CORRECCIÓN: Obtenemos el array de la reserva antes de incluir la vista.
$reserva = getDatosFromReserva(__DIR__ . '/data/reservas.csv');

// CORRECCIÓN: Comprobamos el estado HTTP aquí (lógica pura), antes de que el servidor envíe HTML.
if (empty($reserva)) {
    http_response_code(404);
}

include __DIR__ . '/templates/confirmacion.tpl.php';
