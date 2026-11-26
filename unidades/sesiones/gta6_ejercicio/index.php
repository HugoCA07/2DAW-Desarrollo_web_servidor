<?php

session_start();

include_once(__DIR__ . '/lib/functions.php');

if (!isset($_SESSION['mapa'])) {
    iniciarMapa();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'pintar':
                $isValidRow = isValidRow($_POST['fila']);
                $isValidColumn = isValidColumn($_POST['columna']);
                $isValidCeil = isValidCeil($_POST['casilla']);

                if ($isValidRow) {
                    $_SESSION['lastrow'] = $_POST['fila'];
                } else {
                    $_SESSION['mensajes_flash']['error']['fila'] = 'La fila no es correcta.';
                }

                if ($isValidColumn) {
                    $_SESSION['lastcol'] = $_POST['columna'];
                } else {
                    $_SESSION['mensajes_flash']['error']['columna'] = 'La columna no es correcta.';
                }

                if ($isValidCeil) {
                    $_SESSION['lastceil'] = $_POST['casilla'];
                } else {
                    $_SESSION['mensajes_flash']['error']['celda'] = 'La casilla no es correcta.';
                }


                if ($isValidCeil && $isValidColumn && $isValidRow) {
                    $_SESSION['mensajes_flash']['exito']['pintar'] = 'Se ha pintado la casilla en (' . $_SESSION['lastrow'] . ',' . $_SESSION['lastcol'] . ') correctamente';
                    $_SESSION['mapa'][$_SESSION['lastrow']][$_SESSION['lastcol']] = $_POST['casilla'];
                    unset($_SESSION['lastrow']);
                    unset($_SESSION['lastcol']);
                    unset($_SESSION['lastceil']);
                    header('Location: index.php', true, 302);
                    exit();
                }

                break;
            case 'borrar':
                $isValidRow = isValidRow($_POST['fila']);
                $isValidColumn = isValidColumn($_POST['columna']);

                if ($isValidRow) {
                    $_SESSION['lastrow'] = $_POST['fila'];
                } else {
                    $_SESSION['mensajes_flash']['error']['fila'] = 'La fila no es correcta.';
                }

                if ($isValidColumn) {
                    $_SESSION['lastcol'] = $_POST['columna'];
                } else {
                    $_SESSION['mensajes_flash']['error']['columna'] = 'La columna no es correcta.';
                }

                if ($isValidColumn && $isValidRow) {
                    $_SESSION['mensajes_flash']['exito']['borrar'] = 'Se ha borrado la casilla en (' . $_SESSION['lastrow'] . ',' . $_SESSION['lastcol'] . ') correctamente';
                    $_SESSION['mapa'][$_SESSION['lastrow']][$_SESSION['lastcol']] = null;
                    unset($_SESSION['lastrow']);
                    unset($_SESSION['lastcol']);
                    header('Location: index.php', true, 302);
                    exit();
                }

                break;
            case 'reiniciar':
                unset($_SESSION['mapa']);
                header('Location: index.php', true, 302);
                exit();
        }
    }
}

include(__DIR__ . '/templates/index.tpl.php');
