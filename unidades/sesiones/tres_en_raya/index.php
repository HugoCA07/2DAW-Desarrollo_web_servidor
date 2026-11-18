<?php

include_once(__DIR__ . '/functions/functions.php');

session_start();

if (!isset($_SESSION['partida']['turno'])) {
    $turno = 'X';
    $_SESSION['partida']['turno'] = $turno;
}

if (!isset($_SESSION['partida']['tablero'])) {
    $partida = [
        1 => [1 => '', 2 => '', 3 => ''],
        2 => [1 => '', 2 => '', 3 => ''],
        3 => [1 => '', 2 => '', 3 => '']
    ];
    $_SESSION['partida']['tablero'] = $partida;
}

if (!isset($isVictoria)){
    $isVictoria = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['celda'])) {
        $casilla = obtenercasilla($_POST['celda']);
        if (validarCasilla($casilla)) {
            $_SESSION['partida']['tablero'][$casilla['fila']][$casilla['columna']] = $_SESSION['partida']['turno'];
            $isVictoria = comprobarVictoria($_SESSION['partida']['tablero']);
            if ($isVictoria) {
                $_SESSION['flash_messages']['victoria'] = 'Fin de la partida, el ganador ha sido ' . $_SESSION['partida']['turno'];
            } else {
                if ($_SESSION['partida']['turno'] === 'X') {
                    $_SESSION['partida']['turno'] = 'O';
                } else {
                    $_SESSION['partida']['turno'] = 'X';
                }
                header('Location: index.php',true,302);
                exit;
            }
        }
    }
}

if (isset($_GET['reiniciar']) && $_GET['reiniciar'] === 'true') {
    session_destroy();
    header('Location: index.php',true,302);
    exit;
}

include(__DIR__ . '/templates/index.tpl.php');
