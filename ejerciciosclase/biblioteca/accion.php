<?php

require_once __DIR__ . '/functions/functions.php';

$pdo = conexion();

if (isset($_POST['accion'], $_POST['id'])) {
    $accion = $_POST['accion'];
    $id = (int)$_POST['id'];
    $estadoNuevo = ($accion === 'prestar') ? 0 : 1;
    $filas = cambiarDisponible($pdo, $id, $estadoNuevo);

    if ($filas === 0) {
        echo "No se podido cambiar el estado.";
    }
}

if (isset($_POST['borrar']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    $resultado = borrarLibro($pdo, $id);

    if ($resultado === 0) {
        echo '<h2>Ese libro no existe</h2>';
    } else if ($resultado === -1) {
        echo '<h2>No se puede borrar: el libro tiene préstamos registrados</h2>';
    } else {
        echo '<h2>Libro borrado</h2>';
    }
}
?>

?>

<link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
<a href="index.php">Volver al listado</a>
