<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/src/functions.php');

$tournaments = null;
$torneo_edicion = null;
$mensaje = '';

if (isset($_GET['lista_torneos'])) {
    $tournaments = getTournamentsFromDB();
}

if (isset($_GET['borrar_jugador'])) {
    $isDeleted = borradoDeTorneo();
    if ($isDeleted) {
        header('Location: index.php?lista_torneos=', true, 302);
        exit;
    } else {
        http_response_code(400);
    }
}

if (isset($_GET['accion']) && $_GET['accion'] === 'actualizar_puntos') {
    $torneo_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $puntos = $_POST['puntos'] ?? [];
        if ($torneo_id && !empty($puntos)) {
            if (updatePuntosTorneo($torneo_id, $puntos)) {
                $mensaje = "Puntos actualizados correctamente.";
            } else {
                http_response_code(500);
                $mensaje = "Error al actualizar los puntos.";
            }
        }
    }

    if ($torneo_id) {
        $torneo_edicion = getTorneoWithJugadores($torneo_id);
    }
}

include(__DIR__ . '/src/templates/index.tpl.php');
