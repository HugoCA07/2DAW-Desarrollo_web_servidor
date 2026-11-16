<?php

include __DIR__ . '/functions/functions_reservar.php';

// Ejecutamos validarReserva() ANTES de incluir la plantilla HTML.
// Además, capturamos los valores para pasarlos a la plantilla y hacerlos persistentes
$estado_reserva = validarReserva();
$valores_previos = $estado_reserva['valores'] ?? [];

include __DIR__ . '/templates/reservar.tpl.php';
