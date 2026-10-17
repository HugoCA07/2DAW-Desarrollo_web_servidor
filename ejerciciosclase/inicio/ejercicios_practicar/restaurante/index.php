<?php

require_once __DIR__ . '/functions/functions.php';

$pedidos = obtenerPedidosPendientes(__DIR__ . '/data/pedidos.csv');

require __DIR__ . '/template.tpl.php';
