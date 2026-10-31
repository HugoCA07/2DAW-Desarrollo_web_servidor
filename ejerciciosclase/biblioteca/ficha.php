<?php

include __DIR__ . '/functions/functions.php';

$pdo = conexion();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (obtenerlibro($pdo,$id)){
        $libro = obtenerlibro($pdo,$id);
    }
}

include __DIR__ . '/templates/ficha.tpl.php';