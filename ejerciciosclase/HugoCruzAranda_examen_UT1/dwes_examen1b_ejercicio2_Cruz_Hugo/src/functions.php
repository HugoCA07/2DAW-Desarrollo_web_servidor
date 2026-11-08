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
    return new PDO(
        'mysql:host=localhost;dbname=dwes_examen1b;charset=utf8mb4',
        'examen',
        'examen',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
}

function getTournamentsFromDB(): array
{
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare('SELECT t.id AS torneo_id, t.nombre AS nombre, t.juego AS juego, j.nick AS nick, i.puntos AS puntos, i.jugador_id AS jugador_id FROM torneos t LEFT JOIN inscripciones i ON i.torneo_id = t.id LEFT JOIN jugadores j ON j.id = i.jugador_id ORDER BY t.nombre, i.puntos DESC');
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $torneos = [];
        foreach ($rows as $row) {
            $id = $row['torneo_id'];

            if (!isset($torneos[$id])) {
                $torneos[$id] = [
                    'torneo_id' => $row['torneo_id'],
                    'nombre'    => $row['nombre'],
                    'juego'     => $row['juego'],
                    'jugadores' => []
                ];
            }

            if ($row['jugador_id'] !== null) {
                $torneos[$id]['jugadores'][] = [
                    'jugador_id' => $row['jugador_id'],
                    'nick'       => $row['nick'],
                    'puntos'     => $row['puntos']
                ];
            }
        }

        return $torneos;
    } catch (PDOException $e) {
        return [];
    }
}

function getTorneoWithJugadores(int $torneo_id): ?array
{
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare('SELECT t.id AS torneo_id, t.nombre AS nombre, t.juego AS juego,
                                      j.nick AS nick, i.puntos AS puntos, i.jugador_id AS jugador_id
                               FROM torneos t
                               LEFT JOIN inscripciones i ON i.torneo_id = t.id
                               LEFT JOIN jugadores j ON j.id = i.jugador_id
                               WHERE t.id = :torneo_id
                               ORDER BY i.puntos DESC');
        $stmt->execute(['torneo_id' => $torneo_id]);
        $rows = $stmt->fetchAll();

        if (empty($rows)) {
            return null;
        }

        $torneo = [
            'torneo_id' => $rows[0]['torneo_id'],
            'nombre'    => $rows[0]['nombre'],
            'juego'     => $rows[0]['juego'],
            'jugadores' => []
        ];

        foreach ($rows as $row) {
            if ($row['jugador_id'] !== null) {
                $torneo['jugadores'][] = [
                    'jugador_id' => $row['jugador_id'],
                    'nick'       => $row['nick'],
                    'puntos'     => $row['puntos']
                ];
            }
        }

        return $torneo;
    } catch (PDOException $e) {
        return null;
    }
}

function updatePuntosTorneo(int $torneo_id, array $puntos_jugadores): bool
{
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare('UPDATE inscripciones 
                               SET puntos = :puntos 
                               WHERE torneo_id = :torneo_id AND jugador_id = :jugador_id');

        foreach ($puntos_jugadores as $jugador_id => $puntos) {
            $stmt->execute([
                'puntos'     => (int)$puntos,
                'torneo_id'  => $torneo_id,
                'jugador_id' => (int)$jugador_id
            ]);
        }
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function borradoDeTorneo(): bool
{
    $id_jugador = filter_input(INPUT_GET, 'jugador_id', FILTER_VALIDATE_INT);

    if ($id_jugador > 0) {
        try {
            $pdo = conexion();
            $stmt = $pdo->prepare('DELETE FROM jugadores WHERE id = :id_jugador');
            return $stmt->execute(['id_jugador' => $id_jugador]);
        } catch (PDOException $e) {
            return false;
        }
    } else {
        http_response_code(422);
        return false;
    }
}
