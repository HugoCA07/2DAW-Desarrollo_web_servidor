<?php

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

function pintarReserva()
{
    $reserva = getDatosFromReserva(__DIR__ . '/../data/reservas.csv');

    $resultado = '';

    if (empty($reserva)) {
        $resultado .= '<h2>No hay reservas</h2>';
    } else {
        $resultado .= '<table>';
        $resultado .= '<tr>';
        $resultado .= '<th>Nombre</th>';
        $resultado .= '<th>Email</th>';
        $resultado .= '<th>Pista</th>';
        $resultado .= '<th>Fecha</th>';
        $resultado .= '<th>Hora</th>';
        $resultado .= '<th>Material</th>';
        $resultado .= '<th>Normas</th>';
        $resultado .= '</tr>';
        $resultado .= '<tr>';

        foreach ($reserva as $dato) {
            $resultado .= '<td>' . $dato . '</td>';
        }
        $resultado .= '</tr>';

        $resultado .= '</table>';
    }

    echo $resultado;
}
