<?php

function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function getInputs()
{
    $inputs = [];
    $filtrado_pista = filter_input(INPUT_GET, 'pista');
    $filtrado_fecha = filter_input(INPUT_GET, 'fecha');

    $inputs[] = $filtrado_pista;
    $inputs[] = $filtrado_fecha;

    return $inputs;
}

function leerReservas(string $rutaCSV, array $inputs): array
{
    if (!file_exists($rutaCSV) || ($stream = fopen($rutaCSV, 'r')) === false) {
        return [];
    }

    $todas = [];
    while (($fila = fgetcsv($stream)) !== false) {
        $todas[] = $fila;
    }

    fclose($stream);

    $filtradas = array_filter($todas, function ($fila) use ($inputs) {
        if (!empty($inputs[0]) && (!isset($fila[2]) || trim($fila[2]) !== trim($inputs[0]))) {
            return false;
        }
        if (!empty($inputs[1]) && (!isset($fila[3]) || trim($fila[3]) !== trim($inputs[1]))) {
            return false;
        }
        return true;
    });

    return array_values($filtradas);
}

// CORRECCIÓN: Eliminamos la vieja función pintarTabla() que generaba HTML.
// Ahora esta función solo sirve de puente para obtener los datos puros.
function obtenerReservas($inputs): array
{
    return leerReservas(__DIR__ . '/../data/reservas.csv', $inputs);
}
