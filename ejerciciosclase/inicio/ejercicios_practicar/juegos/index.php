<?php

require_once __DIR__ . '/functions/functions.php';

$csv_path = __DIR__ . '/data/juegos.csv';
$videojuegos = getGamesWithStock($csv_path);

$defaultURL = 'https://ejemplo.com/fotodefault';

require __DIR__ . '/template.tpl.php';
