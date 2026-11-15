<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

/**
 * Estructura de $_SESSION['carrito']:
 * - Una única clave 'carrito' en $_SESSION para poder vaciarla con unset($_SESSION['carrito'])
 *   sin cerrar la sesión del usuario.
 * - Contiene únicamente un array asociativo [producto_id => cantidad], ej: [1 => 2, 4 => 1].
 * - Nunca almacena precios ni nombres (se consultan siempre de la BD).
 */

inicioSesion();

if (!isset($_SESSION['id'])) {
    header('Location: login.php', true, 302);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    switch ($_POST['accion']) {
        case 'vaciar_carro':
            unset($_SESSION['carrito']);
            header('Location: carrito.php', true, 303);
            exit;

        case 'eliminar':
            if ($id > 0) {
                unset($_SESSION['carrito'][$id]);
            }
            header('Location: carrito.php', true, 303);
            exit;

        case 'sumar':
            if ($id > 0 && isset($_SESSION['carrito'][$id])) {
                $stockdb = getStock($id);
                if ($stockdb !== false && $_SESSION['carrito'][$id] < $stockdb) {
                    $_SESSION['carrito'][$id]++;
                } else {
                    $_SESSION['flash_messages']['sumar'] = 'No hay más stock disponible';
                }
            }
            header('Location: carrito.php', true, 303);
            exit;

        case 'restar':
            if ($id > 0 && isset($_SESSION['carrito'][$id])) {
                if ($_SESSION['carrito'][$id] - 1 >= 1) {
                    $_SESSION['carrito'][$id]--;
                } else {
                    unset($_SESSION['carrito'][$id]);
                }
            }
            header('Location: carrito.php', true, 303);
            exit;

        case 'comprar':
            if (!empty($_SESSION['carrito'])) {
                ejecutarCompra($_SESSION['carrito']);
            }
            header('Location: carrito.php', true, 303);
            exit;
    }
}

$productos = !empty($_SESSION['carrito'])
    ? getProductsFromIds(array_keys($_SESSION['carrito']))
    : [];

include(__DIR__ . '/templates/carrito.tpl.php');
