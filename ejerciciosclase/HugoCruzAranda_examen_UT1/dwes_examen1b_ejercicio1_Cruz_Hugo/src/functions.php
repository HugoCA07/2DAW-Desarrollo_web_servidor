<?php

function dump($var): void
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
}

function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function conexion(): PDO
{
    return $pdo = new PDO(
        'mysql:host=localhost;dbname=dwes_examen1b;charset=utf8mb4',
        'examen',
        'examen',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}

function getTournamentsFromDB(): array
{

    try {
        $pdo = conexion();
        $stmt = $pdo->prepare('SELECT t.id AS torneo_id, t.nombre AS nombre, t.juego as juego,
                   j.nick AS nick, i.puntos AS puntos
            FROM torneos t
            LEFT JOIN inscripciones i ON i.torneo_id = t.id
            LEFT JOIN jugadores j ON j.id = i.jugador_id
            ORDER BY t.nombre, i.puntos DESC');

        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}
