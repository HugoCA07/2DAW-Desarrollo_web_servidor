<?php

include __DIR__ . '/functions/functions.php';

$datosLibro = $_POST;

$pdo = conexion();

insertarLibro($pdo,$datosLibro);

include __DIR__ . '/templates/nuevo.tpl.php';
