<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

inicioSesion();

if (!isset($_SESSION['id'])) {
    header('Location: login.php', true, 302);
    exit;
}

$pedidos = getPedidos((int)$_SESSION['id']);

include(__DIR__ . '/templates/bienvenida.tpl.php');
