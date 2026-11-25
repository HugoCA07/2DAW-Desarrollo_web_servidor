<?php

/* ***** INICIALIZACIÓN DEL ENTORNO ***** */

session_start();

include_once(__DIR__ . '/lib/functions.php');

if (!isset($_SESSION['tablero'])) {
    inicializarTablero();
}

if (!isset($_SESSION['fila'])) {
    $_SESSION['fila'] = 1;
}

if (!isset($_SESSION['columna'])) {
    $_SESSION['columna'] = 1;
}

if (!isset($_SESSION['movimientos'])) {
    $_SESSION['movimientos'] = [];
}

$contadorGlobalMovimientos = 0;
$contadorCasillasOcupadas = 0;

/* ***** LÓGICA DE NEGOCIO ***** */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'colocar':
                $validados = validarFilasColumnasDuplicado();
                $isValidFila = $validados[0];
                $isValidColumna = $validados[1];


                $isValidTile = validarTile($_POST['tile']);
                if ($isValidTile) {
                    $_SESSION['tile'] = $_POST['tile'];
                } else {
                    $_SESSION['flash_messages']['error']['tile'] = 'El tile no es correcto';
                }

                $isValidCantidadTile = validarCantidadTile($_POST['tile']);
                if (!$isValidCantidadTile) {
                    $tile = $_POST['tile'];
                    if ($tile === null || $tile === '') {
                        $tile = 'Elige un tile al menos';
                    } else {
                        $tile = 'Limite alcanzado para ' . $_POST['tile'] . ' maximo ' . TILES[$_POST['tile']]['max'];
                    }
                    $_SESSION['flash_messages']['error']['cantidad'] = $tile;
                }

                if ($isValidTile && $isValidColumna && $isValidFila && $isValidCantidadTile) {
                    $_SESSION['tablero'][$_POST['fila']][$_POST['columna']] = $_POST['tile'];
                    $_SESSION['contador'][$_POST['tile']] = $_SESSION['contador'][$_POST['tile']] + 1;
                    $_SESSION['tile'] = null;
                    $_SESSION['fila'] = 1;
                    $_SESSION['columna'] = 1;
                    $_SESSION['flash_messages']['success'] = 'Se ha colocado «' . $_POST['tile'] . '» en la casilla (' . $_POST['fila'] . ',' . $_POST['columna'] . ')';
                    $_SESSION['movimientos'][$contadorGlobalMovimientos] = $_SESSION['tablero'];
                    $contadorGlobalMovimientos++;
                    header('Location: index.php', true, 302);
                    exit();
                }
                break;
            case 'quitar':
                $validados = validarFilasColumnasDuplicado();
                $isValidFila = $validados[0];
                $isValidColumna = $validados[1];

                if ($isValidColumna && $isValidFila) {
                    $_SESSION['tablero'][$_POST['fila']][$_POST['columna']] = null;
                    $_SESSION['contador'][$_POST['tile']] = $_SESSION['contador'][$_POST['tile']] - 1;
                    $_SESSION['movimientos'][$contadorGlobalMovimientos] = $_SESSION['tablero'];
                    $contadorGlobalMovimientos++;
                    $_SESSION['fila'] = 1;
                    $_SESSION['columna'] = 1;
                    $_SESSION['flash_messages']['success'] = 'Se ha borrado la casilla (' . $_POST['fila'] . ',' . $_POST['columna'] . ')';
                    header('Location: index.php', true, 302);
                    exit();
                }
                break;
            case 'reiniciar':
                unset($_SESSION['tablero']);
                header('Location: index.php', true, 302);
                exit();
            case 'deshacer':
                if (isset($_SESSION['movimientos'][$contadorGlobalMovimientos])) {
                    $_SESSION['tablero'] = $_SESSION['movimientos'][$contadorGlobalMovimientos];
                    $contadorGlobalMovimientos--;
                    $_SESSION['flash_messages']['success'] = 'Se ha deshecho el movimiento';
                    header('Location: index.php', true, 302);
                    exit();
                } else {
                    $_SESSION['flash_messages']['error'] = 'No se ha podido deshacer el movimiento';
                    header('Location: index.php', true, 302);
                    exit();
                }
        }
    }
}

/* ***** LÓGICA DE PRESENTACIÓN ***** */

include(__DIR__ . '/templates/index.tpl.php');