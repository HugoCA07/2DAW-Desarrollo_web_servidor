<?php

$tablero = array(
    // Fila 1
    array('bridge1', 'downstairs', 'upstairs', 'sand1', 'sand2', 'sand3'),

    // Fila 2
    array('water1', 'water2', 'water3', 'rock1', 'rock2', 'rock3'),

    // Fila 3
    array('water4', 'water5', 'water6', 'rock4', 'rock5', 'rock6'),

    // Fila 4
    array('water7', 'water8', 'water9', 'rock7', 'greenbush', 'statue'),

    // Fila 5
    array('sand_water1', 'sand_water2', 'sand_water3', 'sand_water4', 'brownbush', 'tombstone'),

    // Fila 6
    array('redrock1', 'redrock2', 'redrock3', 'redrock4', 'whitebush', 'grass'),

    // Fila 7
    array('redrock5', 'redrock6', 'redrock7', 'greyrock1', 'greyrock2', 'greyrock3'),

    // Fila 8
    array('greysand', 'bridge2', 'greyrock4', 'greyrock5', 'greyrock6', 'greyrock7')
);

include __DIR__ . '/template.tpl.php';
