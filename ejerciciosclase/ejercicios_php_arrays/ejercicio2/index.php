<?php

$usuario = [
    "nombre" => "Ana",
    "correo" => "ana@example.com",
    "edad" => 30,
    "activo" => true
];

if ($usuario['activo'] === true) {
    echo $usuario['nombre'] . ' tiene ' . $usuario['edad'] . ' anyos y su cuenta está activa';
} else {
    echo $usuario['nombre'] . ' tiene ' . $usuario['edad'] . ' anyos y su cuenta está inactiva';
}
