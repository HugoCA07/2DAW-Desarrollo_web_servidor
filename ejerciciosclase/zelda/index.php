<?php

function getTableroFromCsv(String $rutaCSV)
{
    $stream = fopen($rutaCSV, 'r');
    $tablero = [];


    if ($stream !== false) {

        while (($fila = fgetcsv($stream)) !== false) {
            $tablero[] = $fila;
        }

        fclose($stream);
    }

    return $tablero;
}

$tablero = getTableroFromCsv(__DIR__ . '/tablero.csv');
$filas = count($tablero);
$columnas = count($tablero[0]);

$link_pos_x = filter_input(INPUT_GET, 'link_pos_x', FILTER_VALIDATE_INT);
$link_pos_y = filter_input(INPUT_GET, 'link_pos_y', FILTER_VALIDATE_INT);

if ($link_pos_x === null || $link_pos_x === false || $link_pos_x < 0 || $link_pos_x >= $columnas) {
    $link_pos_x = 0;
}

if ($link_pos_y === null || $link_pos_y === false || $link_pos_y < 0 || $link_pos_y >= $filas) {
    $link_pos_y = 0;
}

$contador_filas = 0;
$contador_columnas = 0;


include __DIR__ . '/template.tpl.php';
