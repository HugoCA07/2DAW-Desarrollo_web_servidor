<?php

session_start();

include_once(__DIR__ . '/functions/functions.php');

if (!isset($_SESSION['flota']['tablero'])) {
    $_SESSION['flota']['tablero'] = [
        [0, 0, 0, 0],
        [0, 0, 0, 0],
        [0, 0, 0, 0],
        [0, 0, 0, 0]
    ];

    for ($i = 0; $i < 3; $i++) {
        $fila = rand(0, 3);
        $casilla = rand(0, 3);

        $_SESSION['flota']['tablero'][$fila][$casilla] = 1;
    }

    $_SESSION['flota']['intentos'] = 6;
    $_SESSION['flota']['aciertos'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['operacion'])) {
        switch ($_POST['operacion']) {
            case 'shoot':
                if (isset($_POST['casilla']) && is_string($_POST['casilla'])) {
                    $coords = explode(',', $_POST['casilla']);

                    if (count($coords) !== 2) {
                        $_SESSION['flash_messages']['game'] = '¡Coordenada invalida!';
                        header('Location: index.php', true, 302);
                        exit();
                    }

                    if (is_numeric($coords[0])) {
                        $row = intval($coords[0]);
                    } else {
                        $_SESSION['flash_messages']['game'] = 'Fila invalida!';
                        header('Location: index.php', true, 302);
                        exit();
                    }

                    if (is_numeric($coords[1])) {
                        $ceil = intval($coords[1]);
                    } else {
                        $_SESSION['flash_messages']['game'] = 'Celda invalida!';
                        header('Location: index.php', true, 302);
                        exit();
                    }

                    if (validCoordenada($row, $ceil)) {
                        if ($_SESSION['flota']['tablero'][$row][$ceil] === 1) {
                            $_SESSION['flash_messages']['game'] = '¡Le has dado!';
                            $_SESSION['flota']['tablero'][$row][$ceil] = 2;
                            $_SESSION['flota']['aciertos']++;
                        } else {
                            $_SESSION['flota']['tablero'][$row][$ceil] = 3;
                            $_SESSION['flash_messages']['game'] = '¡Fallaste!';
                        }

                        $_SESSION['flota']['intentos']--;

                        header('Location: index.php', true, 302);
                        exit();
                    } else {
                        $_SESSION['flash_messages']['game'] = '¡Coordenada invalida!';
                        header('Location: index.php', true, 302);
                        exit();
                    }
                }

                break;
            case 'restart':
                $_SESSION = [];

                setcookie(session_name(), '', time() - 42000);

                session_destroy();

                header('Location: index.php', true, 302);
                exit();
        }
    }
}

if (isset($_SESSION['flota'])) {
    if (!comprobarTurnos($_SESSION['flota']['intentos'])) {
        $_SESSION['flash_messages']['game'] = 'Te quedaste sin turnos';
    }

    if ($_SESSION['flota']['aciertos'] === 3) {
        $_SESSION['flash_messages']['game'] = 'Buena partida, has ganado';
    }
}

include(__DIR__ . '/templates/index.tpl.php');
