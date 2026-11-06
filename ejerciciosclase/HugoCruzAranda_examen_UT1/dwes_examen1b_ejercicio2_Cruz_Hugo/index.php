<?php

//Startup errors (dev mode)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__.'/src/functions.php');


if (isset($_GET['lista_torneos'])) {
    $tournaments = getTournamentsFromDB();
}

if (isset($_GET['borrar_jugador'])) {
    $isDeleted = borradoDeTorneo();
    if ($isDeleted) {
        header('Location: index.php?lista_torneos=',true, 302);
    } else {
        http_response_code(400);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['actualizar_puntos'])) {
        $isUpdated = updateTorneo();
        if ($isUpdated) {
            header('Location: index.php?lista_torneos=',true, 302);
        }
    } else {
        http_response_code(400);
    }
}

include(__DIR__.'/src/templates/index.tpl.php');
