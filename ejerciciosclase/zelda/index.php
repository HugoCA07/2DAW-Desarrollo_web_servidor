<?php

// $tablero = array(

//     // Fila 1
//     array('rock1', 'rock2', 'rock3', 'rock4', 'rock5', 'rock6', 'rock7', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'rock1', 'rock2', 'rock3', 'rock4', 'rock5', 'rock6', 'rock7', 'grass', 'grass', 'greenbush', 'grass', 'rock1', 'rock2', 'rock3', 'rock4', 'rock5', 'rock6', 'rock7', 'grass', 'grass'),

//     // Fila 2
//     array('rock1', 'sand1', 'sand2', 'sand3', 'sand1', 'sand2', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'sand1', 'sand2', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass'),

//     // Fila 3
//     array('rock4', 'sand4', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5'),

//     // Fila 4
//     array('rock5', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'rock6'),

//     // Fila 5
//     array('rock7', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'rock1'),

//     // Fila 6
//     array('grass', 'grass', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass'),

//     // Fila 7
//     array('grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass'),

//     // Fila 8
//     array('grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'whitebush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass'),

//     // Fila 9
//     array('water1', 'water2', 'water3', 'water1', 'water2', 'water3', 'water4', 'water5', 'water6', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass', 'grass'),

//     // Fila 10
//     array('water4', 'water5', 'water6', 'water4', 'water5', 'water6', 'water7', 'water8', 'water9', 'grass', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2'),

//     // Fila 11
//     array('water7', 'water8', 'water9', 'water7', 'water8', 'water9', 'water1', 'water2', 'water3', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3'),

//     // Fila 12
//     array('upstairs', 'water2', 'water3', 'water4', 'water5', 'water6', 'water4', 'water5', 'water6', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6'),

//     // Fila 13
//     array('downstairs', 'water5', 'water6', 'water7', 'water8', 'water9', 'water7', 'water8', 'water9', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass'),

//     // Fila 14
//     array('water7', 'water8', 'water9', 'water1', 'water2', 'water3', 'water1', 'water2', 'water3', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'grass', 'grass'),

//     // Fila 15
//     array('sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'brownbush', 'sand_water2', 'sand_water3', 'sand_water4', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2'),

//     // Fila 16
//     array('sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass'),

//     // Fila 17
//     array('sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'grass', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass'),

//     // Fila 18
//     array('sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'greenbush', 'grass', 'grass'),

//     // Fila 19
//     array('grass', 'grass', 'grass', 'grass', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass'),

//     // Fila 20
//     array('grass', 'greenbush', 'grass', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass'),

//     // Fila 21
//     array('redrock1', 'redrock2', 'redrock3', 'redrock4', 'grass', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass'),

//     // Fila 22
//     array('redrock5', 'redrock6', 'redrock7', 'redrock1', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass'),

//     // Fila 23
//     array('redrock2', 'redrock3', 'redrock4', 'redrock5', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass'),

//     // Fila 24
//     array('redrock6', 'redrock7', 'redrock1', 'redrock2', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'greenbush', 'grass', 'grass'),

//     // Fila 25
//     array('greyrock1', 'greyrock2', 'greyrock3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass'),

//     // Fila 26
//     array('greyrock4', 'greyrock5', 'greyrock6', 'greyrock7', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass', 'grass'),

//     // Fila 27
//     array('greyrock1', 'greyrock2', 'greyrock3', 'greyrock4', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'greenbush', 'grass', 'grass', 'grass'),

//     // Fila 28
//     array('greyrock5', 'greyrock6', 'greyrock7', 'greyrock1', 'grass', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass'),

//     // Fila 29
//     array('greysand', 'greysand', 'greysand', 'greysand', 'greysand', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'greenbush', 'grass', 'grass', 'sand1', 'sand2', 'sand3', 'grass', 'grass', 'grass'),

//     // Fila 30
//     array('greysand', 'greysand', 'greysand', 'greysand', 'greysand', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'sand4', 'sand5', 'sand6', 'grass', 'grass', 'grass'),

//     // Fila 31
//     array('greysand', 'greysand', 'greysand', 'greysand', 'greysand', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass'),

//     // Fila 32
//     array('greysand', 'greysand', 'bridge2', 'bridge2', 'bridge2', 'bridge2', 'bridge2', 'greysand', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'greenbush', 'grass', 'grass', 'grass', 'grass', 'grass')
// );

$link_pos_x = filter_input(INPUT_GET, 'link_pos_x', FILTER_VALIDATE_INT);
$link_pos_y = filter_input(INPUT_GET, 'link_pos_y', FILTER_VALIDATE_INT);

if ($link_pos_x === null || $link_pos_x === false) {
    $link_pos_x = 0;
}

if ($link_pos_y === null || $link_pos_y === false) {
    $link_pos_y = 0;
}

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

$contador_filas = 0;
$contador_columnas = 0;


include __DIR__ . '/template.tpl.php';
