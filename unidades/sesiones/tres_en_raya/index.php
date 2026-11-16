<?php

include_once (__DIR__ . '/functions/functions.php');

session_start();

if (!isset($_SESSION['partida']['turno'])) {
    $turno = 'X';
    $_SESSION['partida']['turno'] = $turno;
}

if (!isset($_SESSION['partida']['tablero'])) {
    $partida = [
        'fila1' => [
            'celda1' => '',
            'celda2' => '',
            'celda3' => ''
        ],
        'fila2' => [
            'celda1' => '',
            'celda2' => '',
            'celda3' => ''
        ],
        'fila3' => [
            'celda1' => '',
            'celda2' => '',
            'celda3' => ''
        ]
    ];
    $_SESSION['partida']['tablero'] = $partida;
}



include (__DIR__ . '/templates/index.tpl.php');