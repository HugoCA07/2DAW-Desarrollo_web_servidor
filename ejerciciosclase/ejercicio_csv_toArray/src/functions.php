<?php

function dump(mixed $var)
{
  echo '<pre>' . print_r($var, 1) . '</pre>';
}


function getCSVContentInArray(String $rutaCSV, String $param)
{
  $contenidoArchivo = file_get_contents($rutaCSV);
  $contenidoArchivo = trim($contenidoArchivo);
  $lineasCSV = explode("\n", $contenidoArchivo);

  $encabezadoCadena = reset($lineasCSV);
  $encabezadoArray = explode(',', $encabezadoCadena);

  $encabezadoArrayNuevo = array_map('trim', $encabezadoArray);

  $ArrayResultado = [];

  $indiceclave = 0;

  foreach ($encabezadoArrayNuevo as $clavesarraynuevo) {
    $indiceclave++;
    if ($clavesarraynuevo === $param) {
      break;
    }
  }

  foreach ($lineasCSV as $clave => $linea) {
    if ($clave != 0) {
      $camposLinea = explode(',', $linea);

      $ArrayResultado[$camposLinea[$indiceclave - 1]] = array_combine($encabezadoArrayNuevo, $camposLinea);
    }
  }
  return $ArrayResultado;
}

function getCSVContentInArrayProfe(String $rutaCSV, String|null $nombreClave = null)
{

  $contenidoArchivo = file_get_contents($rutaCSV);
  $contenidoArchivo = trim($contenidoArchivo);
  $lineasCSV = explode("\n", $contenidoArchivo);

  $encabezadoCadena = reset($lineasCSV);
  $encabezadoArray = explode(',', $encabezadoCadena);

  $encabezadoArrayNuevo = array_map('trim', $encabezadoArray);

  $arrayResultados = [];

  foreach ($lineasCSV as $clave => $linea) {
    if ($clave != 0) {
      $camposLinea = explode(',', $linea);

      $elemento = array_combine($encabezadoArrayNuevo, $camposLinea);

      if (isset($nombreClave)) {
        $valorClave = trim($elemento[$nombreClave]);
        $arrayResultados[$valorClave] = $elemento;
      } else {
        $arrayResultados[] = $elemento;
      }
    }
  }
  return $arrayResultados;
}

function getMarkUpFromDataCars(array $data)
{
  $output = '';

  $output .= '<ul>';

  foreach ($data as $clave => $valor) {
    $output .= '<li>';
    $output .= $valor['Marca'];
    $output .= '</li>';
  }

  $output .= '</ul>';

  return $output;
}
