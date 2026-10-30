<?php

include __DIR__ . '/functions/functions.php';

$posiciones = setupSession();

$link_pos_x = $posiciones['posX'] ?? 0;
$link_pos_y = $posiciones['posY'] ?? 0;

$tablero = getTableroFromCsv(__DIR__ . '/data/tablero.csv');
$filas = count($tablero);
$columnas = $filas > 0 ? count($tablero[0]) : 0;


if (isset($_POST['direccion'])) {
    switch ($_POST['direccion']) {
        case 'arriba':
            if (posicionValida($link_pos_x, $link_pos_y - 1, $tablero)){
                $link_pos_y--;
            }
            break;
        case 'abajo':
            if (posicionValida($link_pos_x, $link_pos_y + 1, $tablero)){
                $link_pos_y++;
            }
            break;
        case 'derecha':
            if (posicionValida($link_pos_x + 1, $link_pos_y, $tablero)){
                $link_pos_x++;
            }
            break;
        case 'izquierda':
            if (posicionValida($link_pos_x - 1, $link_pos_y, $tablero)){
                $link_pos_x--;
            }
            break;
    }
}

$_SESSION['link_pos_x'] = $link_pos_x;
$_SESSION['link_pos_y'] = $link_pos_y;

include __DIR__ . '/template/index.tpl.php';
