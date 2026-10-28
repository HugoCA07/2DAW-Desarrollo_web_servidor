<?php

include __DIR__ . "/functions/functions.php";

$dbh = conexion();

echo "<h2> LIBROS DISPONIBLES: " . contarDisponibles($dbh) . "</h2>";

$inputs = leerInputs();
$libros = listarLibros($dbh, $inputs['genero']);

include __DIR__ . "/templates/index.tpl.php";