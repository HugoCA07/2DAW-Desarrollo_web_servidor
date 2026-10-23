<?php

function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function getHashFromURL()
{
    return filter_input(INPUT_GET, 'hashreserva', FILTER_UNSAFE_RAW);
}

function getDatosFromReserva(string $rutaCSV)
{
    $hash_buscar = getHashFromURL();
    $resultado = [];

    if (!file_exists($rutaCSV) || ($stream = fopen($rutaCSV, 'r')) === false) {
        return false;
    }

    while (($fila = fgetcsv($stream)) !== false) {
        if (isset($fila[2], $fila[3], $fila[4])) {
            $hashFila = hash("murmur3a", ($fila[3] . '_' . $fila[4] . '_' . $fila[2]));
            if ($hashFila === $hash_buscar) {
                $resultado = $fila;
                break;
            }
        }
    }

    fclose($stream);

    return $resultado;
}