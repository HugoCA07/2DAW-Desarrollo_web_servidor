<?php

declare(strict_types=1);

require_once __DIR__ . '/functions/functions.php';

$pdo = conexion();

// CAMBIO: los mensajes se guardan en variables y los pinta la plantilla (antes se hacía echo
// sin <html> ni <body>). $mensaje es el texto y $esError sirve para darle otro estilo.
$mensaje = '';
$esError = false;

// CAMBIO: esta página solo tiene sentido por POST. Si se entra por URL, se vuelve al listado.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';

if ($id === false || $id === null) {
    $mensaje = 'Falta el id del libro o no es válido.';
    $esError = true;

} elseif (isset($_POST['borrar'])) {
    $resultado = borrarLibro($pdo, $id);

    if ($resultado === 0) {
        $mensaje = 'Ese libro no existe';
        $esError = true;
    } elseif ($resultado === -1) {
        // CAMBIO: el mensaje ya no habla de "no está disponible", solo de los préstamos
        $mensaje = 'No se puede borrar: el libro tiene préstamos registrados';
        $esError = true;
    } elseif ($resultado === -2) {
        $mensaje = 'Error interno al intentar eliminar el libro';
        $esError = true;
    } else {
        $mensaje = 'Libro borrado con éxito';
    }

// CAMBIO: se comprueba explícitamente "prestar" o "devolver" (antes todo lo que no era
// "prestar" se trataba como devolver)
} elseif ($accion === 'prestar' || $accion === 'devolver') {
    $libro = obtenerlibro($pdo, $id);

    if (!$libro) {
        $mensaje = 'Ese libro no existe';
        $esError = true;
    } else {
        $estadoNuevo = ($accion === 'prestar') ? 0 : 1;

        // CAMBIO: se comprueba el estado actual antes, porque si ya tenía ese estado
        // rowCount() devuelve 0 y parecía un error cuando no lo era
        if ($libro['disponible'] === $estadoNuevo) {
            $mensaje = ($accion === 'prestar') ? 'Ese libro ya estaba prestado' : 'Ese libro ya estaba disponible';
            $esError = true;
        } else {
            $filas = cambiarDisponible($pdo, $id, $estadoNuevo);

            if ($filas === 0) {
                $mensaje = 'No se ha podido cambiar el estado.';
                $esError = true;
            } else {
                $mensaje = ($accion === 'prestar') ? 'Libro prestado con éxito' : 'Libro devuelto con éxito';
            }
        }
    }

} else {
    $mensaje = 'Acción no válida.';
    $esError = true;
}

include __DIR__ . '/templates/accion.tpl.php';