<?php

include_once(__DIR__ . '/src/functions.php');

$result = getTournamentsWithPlayersFromBBDD();

if (is_array($result)) {
    $tournaments = $result;
} else {
    $error_msg = $result;
}

if (isset($_GET['op'])) {
    $accion = $_GET['op'];
    switch ($accion) {
        case 'listar_torneos':
            $tournaments = getTournamentsWithPlayersFromBBDD();
            break;
        case 'borrar_jugador':
            $id_player = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($id_player === null || !$id_player) {
                $error_msg = 'El id no es correcto';
            } else {
                $error_msg = deletePlayerFromTournament($id_player);
                header('Location: index.php?op=listar_torneos', true, 303);
                exit;
            }

            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['op'];
    if ($accion !== 'actualizar_puntos') {
        $error_msg = 'Acción del formulario incorrecta';
    } else {
        $id_torneo = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $error_msg = updatePointsFromTournament($id_torneo);
    }
}

include(__DIR__ . '/src/templates/index.tpl.php');
