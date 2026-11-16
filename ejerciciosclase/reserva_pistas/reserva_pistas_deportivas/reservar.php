<?php

include __DIR__ . '/functions/functions_reservar.php';

// CORRECCIÓN: Ejecutamos validarReserva() ANTES de incluir la plantilla HTML.
// Esto es vital porque si validamos después y hay que hacer una redirección (header), 
// PHP fallará si ya se ha enviado el HTML. 
// Además, capturamos los valores para pasarlos a la plantilla y hacerlos "persistentes".
$estado_reserva = validarReserva();
$valores_previos = $estado_reserva['valores'] ?? [];

include __DIR__ . '/templates/reservar.tpl.php';
