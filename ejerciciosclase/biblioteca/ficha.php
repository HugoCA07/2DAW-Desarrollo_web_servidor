<?php

declare(strict_types=1);

// CAMBIO: require_once, igual que en el resto de archivos
require_once __DIR__ . '/functions/functions.php';

$pdo = conexion();

// CAMBIO: FILTER_VALIDATE_INT en vez de is_numeric (que acepta cosas como 1.5 o 1e3).
// Devuelve null si no viene el parámetro y false si no es un entero.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Distingue "todavía no ha buscado nada" de "ha buscado y no existe"
$buscado = ($id !== null);
$libro = false;

if ($id !== null && $id !== false) {
    // CAMBIO: una sola consulta (antes obtenerlibro() se llamaba dos veces)
    $libro = obtenerlibro($pdo, $id);
}

include __DIR__ . '/templates/ficha.tpl.php';