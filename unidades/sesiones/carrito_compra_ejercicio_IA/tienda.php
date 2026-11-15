<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

inicioSesion();

if (!isset($_SESSION['id'])) {
    header('Location: login.php', true, 303);
    exit;
}

$productos = getProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id'])) {

        $id_producto = $_POST['id'];

        if (validarProducto($id_producto)) {
            $stockBBDD = getStock($id_producto);

            $cantidadActual = $_SESSION['carrito'][$id_producto] ?? 0;

            if ($cantidadActual + 1 <= $stockBBDD) {
                $_SESSION['carrito'][$id_producto] = $cantidadActual + 1;
                header('Location: tienda.php', true, 302);
                exit;
            } else {
                $_SESSION['flash_messages']['producto'] = 'No se puede superar el stock del almacén';
            }
        } else {
            $_SESSION['flash_messages']['producto'] = 'El producto no es valido';
        }
    }
}


include(__DIR__ . '/templates/tienda.tpl.php');
