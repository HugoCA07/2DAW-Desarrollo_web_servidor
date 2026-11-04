<?php

declare(strict_types=1);

require_once __DIR__ . '/functions/functions.php';

$pdo = conexion();

$errores = [];
$datosForm = [];   // para no perder lo que escribió el usuario si hay errores

// CAMBIO: solo se intenta insertar si de verdad se ha enviado el formulario.
// Antes se llamaba a insertarLibro() también al entrar por GET.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datosForm = $_POST;
    [$datosLimpios, $errores] = validarLibro($_POST);

    if (!$errores) {
        $resultado = insertarLibro($pdo, $datosLimpios);

        if ($resultado > 0) {
            // Post/Redirect/Get: así al recargar la página no se reenvía el formulario
            header('Location: nuevo.php?creado=' . $resultado);
            exit;
        } else if ($resultado === -1) {
            $errores[] = "Ya existe un libro con ese ISBN.";
        } else {
            $errores[] = "Error interno al intentar añadir el libro.";
        }
    }
}

// Mensaje de éxito tras la redirección
$creado = filter_input(INPUT_GET, 'creado', FILTER_VALIDATE_INT);

include __DIR__ . '/templates/nuevo.tpl.php';