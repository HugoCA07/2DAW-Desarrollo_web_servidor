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
?>

<link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
<a href="index.php">Volver al listado</a>
