<?php

function conexion(): PDO|false
{
    try {
        return new PDO('mysql:host=localhost;dbname=login_dwes',
            'dwes',
            'dwes',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
        );
    } catch (PDOException) {
        return false;
    }
}