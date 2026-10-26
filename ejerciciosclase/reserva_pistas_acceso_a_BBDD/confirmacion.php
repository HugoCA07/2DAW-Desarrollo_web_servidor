<?php

include __DIR__ . '/functions/functions_confirmacion.php';

// Obtenemos el array de la reserva antes de incluir la vista.
$credBBDD = [
    'dsn' => 'mysql:host=localhost;dbname=reservapistas;charset=utf8mb4',
    'username' => 'dwes',
    'password' => 'abc123.',
];
$reserva = getDatosFromReserva($credBBDD);

// Comprobamos el estado HTTP aquí, antes de enviar HTML.
if (empty($reserva)) {
    http_response_code(404);
}

include __DIR__ . '/templates/confirmacion.tpl.php';
