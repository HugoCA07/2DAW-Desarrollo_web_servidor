<?php

function dump(mixed $var)
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function e(string $e): string
{
    return htmlspecialchars($e);
}

function conexion(): PDO|null
{
    try {
        return new PDO(
            'mysql:host=localhost;dbname=dwes_examen1b;charset=utf8mb4',
            'examen',
            'examen',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    } catch (PDOException $e) {
        error_log("Error de conexión PDO: " . $e->getMessage());
        return null;
    }
}

function getTournamentsWithPlayersFromBBDD(): array|string
{
    $pdo = conexion();
    if ($pdo === null) return 'Ha fallado la conexión con la BBDD';

    try {
        $stmt = $pdo->prepare('SELECT t.id AS torneo_id, t.nombre AS nombre, t.juego as juego,
                   j.nick AS nick, i.puntos AS puntos, i.jugador_id AS id_jugador
            FROM torneos t
            LEFT JOIN inscripciones i ON i.torneo_id = t.id
            LEFT JOIN jugadores j ON j.id = i.jugador_id
            ORDER BY t.nombre, i.puntos DESC');
        $stmt->execute();
        $playersInTournaments = $stmt->fetchAll();

        $tournaments = [];

        foreach ($playersInTournaments as $row) {
            $torneoId = $row['torneo_id'];

            if (!isset($tournaments[$torneoId])) {
                $tournaments[$torneoId] = [
                    'torneo_id' => $torneoId,
                    'nombre'    => $row['nombre'],
                    'juego'     => $row['juego'],
                    'jugadores' => []
                ];
            }

            if ($row['nick'] !== null) {
                $tournaments[$torneoId]['jugadores'][] = [
                    'id_jugador' => $row['id_jugador'],
                    'nick'   => $row['nick'],
                    'puntos' => $row['puntos']
                ];
            }
        }

        return $tournaments;
    } catch (PDOException) {
        return 'Ha habido algun error con la BBDD';
    }
}

function deletePlayerFromTournament(int $id_player): bool|string
{
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare('DELETE FROM jugadores WHERE id = :id');
        $stmt->execute(['id' => $id_player]);

        $rows = $stmt->rowCount();

        return ($rows === 1) ? true : false;
    } catch (PDOException) {
        return 'Ha habido algún error con la BBDD';
    }
}

function updatePointsFromTournament(int $id_tournament)
{
    try {
        $pdo = conexion();

        $puntos = filter_input(INPUT_POST, 'puntos', FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);

        $stmt = $pdo->prepare('UPDATE inscripciones SET puntos = :puntos WHERE torneo_id = :torneo_id AND jugador_id = :jugador_id');

        foreach ($puntos as $id => $puntos_player) {

            $stmt->execute([
                'puntos' => (int)$puntos_player,
                'torneo_id' => (int)$id_tournament,
                'jugador_id' => (int)$id
            ]);
        }

        header('Location: index.php?op=listar_torneos', true, 303);
    } catch (PDOException) {
        return 'Ha habido algún error con la BBDD';
    }
}
