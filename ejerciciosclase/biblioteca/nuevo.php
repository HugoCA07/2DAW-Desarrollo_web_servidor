<?php

$datosLibro = $_POST;
echo $datosLibro['isbn'];
echo $datosLibro['titulo'];
echo $datosLibro['autor'];
echo $datosLibro['anio'];
echo $datosLibro['genero'];

include __DIR__ . '/templates/nuevo.tpl.php';
