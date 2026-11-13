<?php

function conexion(): PDO|null
{
    try {
        return new PDO(
            "mysql:host=localhost;dbname=login_dwes",
            "examen",
            "examen",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
    } catch (PDOException) {
        return null;
    }
}