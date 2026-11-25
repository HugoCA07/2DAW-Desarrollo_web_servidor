<?php

/*  ***** INICIALIZACIÓN DEL ENTORNO ***** */
session_start();

include_once(__DIR__ . '/lib/funciones.php');

if (!isset($_SESSION['colores']['tablero'])) {
    for ($i = 1; $i <= TAMANO; $i++) {
        $_SESSION['colores']['tablero'][$i] = [];
        for ($j = 1; $j <= TAMANO; $j++) {
            $_SESSION['colores']['tablero'][$i][$j] = null;
        }
    }
}

if (!isset($_SESSION['colores']['casillas_pintadas'])) {
    $_SESSION['colores']['casillas_pintadas'] = 0;
}

if (!isset($_SESSION['colores']['casillas_totales'])) {
    $_SESSION['colores']['casillas_totales'] = TAMANO * TAMANO;
}

if (!isset($_SESSION['colores']['contadores'])) {
    foreach (PALETA as $color) {
        $_SESSION['colores']['contadores'][$color] = 0;
    }
}


/*  ***** LÓGICA DE NEGOCIO ***** */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] === 'reiniciar') {
        unset($_SESSION['colores']['tablero']);
        $_SESSION['mensajes_flash']['tablero'] = 'Se ha reiniciado correctamente';
        header('Location: index.php', true, 302);
        exit();
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'deshacer') {
        deshacerCambio();
    }

    if (isset($_POST['fila']) && isValidInput($_POST['fila'])) {

        if (isset($_POST['columna']) && isValidInput($_POST['columna'])) {

            if (isset($_POST['color']) && isValidColor($_POST['color'])) {

                switch ($_POST['accion']) {
                    case 'pintar':
                        pintar();
                    case 'borrar':
                        $_SESSION['colores']['acciones'][] = [
                            'color_inicial' => $_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']],
                            'fila' => $_POST['fila'],
                            'columna' => $_POST['columna']
                        ];
                        if (borrarCasilla()) {
                            $_SESSION['mensajes_flash']['borrar'] = 'Se ha borrado correctamente la casilla';
                            $_SESSION['colores']['casillas_pintadas']--;
                        } else {
                            $_SESSION['mensajes_flash']['borrar'] = 'Esa casilla ya estaba vacía antes';
                        }
                        header('Location: index.php', true, 302);
                        exit();
                }
            } else {
                $_SESSION['mensajes_flash']['color'] = 'El color no es correcto o no ha llegado correctamente';
            }
        } else {
            $_SESSION['mensajes_flash']['columna'] = 'La columna no es correcta o no ha llegado correctamente';
        }
    } else {
        $_SESSION['mensajes_flash']['fila'] = 'La fila no es correcta o no ha llegado correctamente';
    }
}

/*  ***** LÓGICA DE PRESENTACIÓN ***** */

include_once(__DIR__ . '/templates/index.tpl.php');