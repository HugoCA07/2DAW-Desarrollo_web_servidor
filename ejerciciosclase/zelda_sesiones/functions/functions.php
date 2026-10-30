<?php

function setupSession(): array
{
    session_start();

    $link_pos_x = $_SESSION['link_pos_x'] ?? 0;
    $link_pos_y = $_SESSION['link_pos_y'] ?? 0;

    return['posX' => $link_pos_x, 'posY' => $link_pos_y];
}
function getTableroFromCsv(String $rutaCSV): array
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

function posicionValida($link_pos_x, $link_pos_y, $tablero): bool
{
    $isValid = false;

    if (isset($tablero[$link_pos_y][$link_pos_x])) {
        $celda = $tablero[$link_pos_y][$link_pos_x];

        if (!str_contains($celda, 'water') && !str_contains($celda, 'rock')) {
            $isValid = true;
        }
    }

    return $isValid;
}