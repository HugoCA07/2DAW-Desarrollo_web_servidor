<?php

declare(strict_types=1);

require_once __DIR__ . "/functions/functions.php";

$dbh = conexion();

// CAMBIO: antes se hacía echo aquí (salía antes del <!doctype>). Ahora se pasa a la plantilla.
$disponibles = contarDisponibles($dbh);

// CAMBIO: leerInputs() se llamaba dos veces. Ahora una sola, y obtenerGeneroFiltro() evita el warning
// cuando la URL no trae "genero" (por ejemplo ?foo=1).
$inputs = leerInputs() ?: [];
$generoActual = obtenerGeneroFiltro($inputs);

$libros = listarLibros($dbh, $generoActual);

include __DIR__ . "/templates/index.tpl.php";