<?php

function getCSVContentInArrayProfe(String $rutaCSV, String|null $nombreClave = null)
{
    $lineasCSV = file($rutaCSV, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (empty($lineasCSV)) {
        return [];
    }

    $encabezadoCadena = array_shift($lineasCSV);
    $encabezadoArray = str_getcsv($encabezadoCadena, ',');

    $arrayResultados = [];

    foreach ($lineasCSV as $valor) {

        $camposLinea = str_getcsv($valor, ',');
        
        while (count($camposLinea) < count($encabezadoArray)) {
            $camposLinea[] = null;
        }

        $camposLinea = array_slice($camposLinea, 0, count($encabezadoArray));

        $elemento = array_combine($encabezadoArray, $camposLinea);

        if ($nombreClave !== null && isset($elemento[$nombreClave])) {

            $valorClave = trim($elemento[$nombreClave]);
            $arrayResultados[$valorClave] = $elemento;

        } else {

            $arrayResultados[] = $elemento;
        }
    }

    return $arrayResultados;
}

?>