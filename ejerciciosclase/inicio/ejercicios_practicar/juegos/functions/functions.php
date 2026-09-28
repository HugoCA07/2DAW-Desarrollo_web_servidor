<?php

function getGamesWithStock(string $url_csv)
{
    $stream = fopen($url_csv, 'r');
    $gameswithstock = [];


    if ($stream !== false) {

        $encabezado = fgetcsv($stream);

        while (($fila = fgetcsv($stream)) !== false) {
            if ($fila[3] > 0) {
                $gameswithstock[] = array_combine($encabezado, $fila);
            }
        }

        fclose($stream);
    }

    return $gameswithstock;
}
