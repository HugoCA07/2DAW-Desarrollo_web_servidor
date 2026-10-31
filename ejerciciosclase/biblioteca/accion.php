<?php

require_once __DIR__ . '/functions/functions.php';

$pdo = conexion();

$id = (int)$_POST['id'];
$accion = $_POST['accion'];

$estadoNuevo = ($accion === 'prestar') ? 0 : 1;

$filas = cambiarDisponible($pdo, $id, $estadoNuevo);

if ($filas === 0) {
    echo "No se podido cambiar el estado.";
}

if ($_POST['borrar']) {
    if (borrarLibro($pdo, $id) === 0) {
        echo '<h2>Ese libro no existe</h2>';
    } else if (borrarLibro($pdo, $id) === -1) {
        echo '<h2>No se puede borrar: el libro tiene prestamos registrados</h2>';
    } else {
        echo '<h2>Libro borrado</h2>';
    }
}

?>

<link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
<a href="index.php">Volver al listado</a>
