<?php

$usuarios = [
    [
        "nombre" => "Alejandro Sanz",
        "edad" => 29,
        "correo" => "alejandro@email.com"
    ],
    [
        "nombre" => "María López",
        "edad" => 34,
        "correo" => "maria.lopez@email.com"
    ],
    [
        "nombre" => "Carlos Gómez",
        "edad" => 22,
        "correo" => "carlos.gomez@email.com"
    ]
];

foreach ($usuarios as $clave => $valor) {
    echo '<pre>';
    print_r('usuario numero ' . $clave . ' se llama ' . $valor['nombre'] . ', tiene ' . $valor['edad'] . ' y su correo es ' . $valor['correo']);
    echo '</pre>';
}
