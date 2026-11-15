<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

inicioSesion();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$pedidos = getPedidos($_SESSION['id']);

include(__DIR__ . '/templates/bienvenida.tpl.php');
