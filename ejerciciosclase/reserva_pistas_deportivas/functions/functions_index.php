<?php

function getInputs()
{
    // LOS INPUTS LOS METO EN UN ARRAY
    $inputs = [];

    $filtrado_pista = filter_input(INPUT_GET, 'pista');
    // LA FECHA ES UN DIA ESPECIFICO
    $filtrado_fecha = filter_input(INPUT_GET, 'fecha');

    array_push($inputs, $filtrado_pista);
    array_push($inputs, $filtrado_fecha);

    return $inputs;
}

function leerReservas(string $rutaCSV, array $inputs)
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

        if ($inputs[0] !== null && (!isset($fila[2]) || trim($fila[2]) !== trim($inputs[0]))) {
            return false;
        }

        if ($inputs[1] !== "" && (!isset($fila[3]) || trim($fila[3]) !== trim($inputs[1]))) {
            return false;
        }

        return true;
    });

    return array_values($filtradas);
}


function pintarTabla()
{
    $inputs = getInputs();
    // USA LA OTRA FUNCION PARA PINTAR (PINTA DIRECTAMENTE)
    $tabla_reservas = leerReservas(__DIR__ . '/data/reservas.csv', $inputs);

    $resultado = '';



    if (empty($tabla_reservas)) {
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
        foreach ($tabla_reservas as $reserva) {
            $resultado .= '<tr>';
            foreach ($reserva as $valor) {
                $resultado .= '<td>' . $valor . '</td>';
            }
            $resultado .= '</tr>';
        }

        $resultado .= '</table>';
    }

    echo $resultado;
}
