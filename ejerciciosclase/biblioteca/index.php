<?php

include __DIR__ . "/functions/functions.php";

$dbh = conexion();

echo "<h2> LIBROS DISPONIBLES: " . contarDisponibles($dbh) . "</h2>";

if (leerInputs()) {
    $inputs = leerInputs();
} else {
    $inputs = ['genero' => 'Todos'];
}

$libros = listarLibros($dbh, $inputs['genero']);

include __DIR__ . "/templates/index.tpl.php";