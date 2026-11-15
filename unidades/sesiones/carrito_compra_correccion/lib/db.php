<?php

function conexion(): PDO|null
{
    try {
        return new PDO(
            'mysql:host=localhost;dbname=tienda_dwes;charset=utf8mb4',
            'examen',
            'examen',
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false
            ]
        );
    } catch (PDOException) {
        return null;
    }
}
