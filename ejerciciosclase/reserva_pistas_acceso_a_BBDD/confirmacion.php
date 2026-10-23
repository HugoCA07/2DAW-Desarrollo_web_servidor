<?php

include __DIR__ . '/functions/functions_confirmacion.php';

// Obtenemos el array de la reserva antes de incluir la vista.
$reserva = getDatosFromReserva(__DIR__ . '/data/reservas.csv');

// Comprobamos el estado HTTP aquí, antes de enviar HTML.
if (empty($reserva)) {
    http_response_code(404);
}

include __DIR__ . '/templates/confirmacion.tpl.php';
