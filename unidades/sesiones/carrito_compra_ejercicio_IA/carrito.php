<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

/**
 * La estructura que va a seguir $_SESSION:
 * $_SESSION['carrito'] para toda la sesion
 * Dentro tendrá el id del usuario al que le pertenece ese carrito
 * Dentro también tendrá un array de productos, que tendrán producto_id => cantidad
 */

inicioSesion();

if (!isset($_SESSION['id'])) {
    header('Location: login.php', true, 303);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'vaciar_carro':
                unset($_SESSION['carrito']);
                header('Location: carrito.php', true, 302);
                exit;
            case 'eliminar':
                unset($_SESSION['carrito'][$_POST['id']]);
                header('Location: carrito.php', true, 302);
                exit;
            case 'sumar':
                $stockdb = getStock($_POST['id']);
                if ($_SESSION['carrito'][$_POST['id']] < $stockdb) {
                    $_SESSION['carrito'][$_POST['id']]++;
                } else {
                    $_SESSION['flash_messages']['sumar'] = 'No hay más stock';
                }
                header('Location: carrito.php', true, 302);
                exit;
            case 'restar':
                if ($_SESSION['carrito'][$_POST['id']] - 1 >= 1) {
                    $_SESSION['carrito'][$_POST['id']]--;
                } else {
                    unset($_SESSION['carrito'][$_POST['id']]);
                }
                header('Location: carrito.php', true, 302);
                exit;
            case 'comprar':
                if (!empty($_SESSION['carrito'])) {
                    ejecutarCompra($_SESSION['carrito'], $_POST['total']);
                }
                header('Location: carrito.php', true, 302);
                exit;
        }
    }
}

$productos = getProductsFromIds(array_keys($_SESSION['carrito']));

include(__DIR__ . '/templates/carrito.tpl.php');
