<?php

$datosLibro = $_POST;
echo $datosLibro['isbn'];
echo $datosLibro['titulo'];
echo $datosLibro['autor'];
echo $datosLibro['anio'];
echo $datosLibro['genero'];

echo  '<pre>';
print_r($datosLibro);
echo '</pre>';

$pdo = conexion();

insertarLibro($pdo,$datosLibro);

include __DIR__ . '/templates/nuevo.tpl.php';
