<?php

use JetBrains\PhpStorm\NoReturn;

const TAMANO = 6;

const PALETA = [
    'rojo'  => 'Rojo',
    'azul'  => 'Azul',
    'verde'  => 'Verde',
    'amarillo'  => 'Amarillo',
    'morado'  => 'Morado',
    'naranja'  => 'Naranja',
];

function dump(mixed $var): void
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function isValidInput(int $input): bool
{
    if (!is_numeric($input)) {
        return false;
    } else if ($input <= 0 || $input > TAMANO) {
        return false;
    }
    return true;
}

function isValidColor(string $color): bool
{
    if (in_array($color, PALETA)) {
        return true;
    }

    return false;
}

function borrarCasilla(): bool
{
    if ($_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']] === null) {
        return false;
    } else {
        $_SESSION['colores']['contadores'][$_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']]]--;
        $_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']] = null;
        return true;
    }
}

#[NoReturn]
function pintar(): void
{
    $_SESSION['colores']['acciones'][] = [
        'color_inicial' => $_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']],
        'fila' => $_POST['fila'],
        'columna' => $_POST['columna']
    ];
    if ($_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']] === $_POST['color']) {
        $_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']] = $_POST['color'];
    } else {
        $_SESSION['colores']['tablero'][$_POST['fila']][$_POST['columna']] = $_POST['color'];
        $_SESSION['colores']['contadores'][$_POST['color']]++;
        $_SESSION['colores']['casillas_pintadas']++;
    }
    $_SESSION['mensajes_flash']['color'] = 'Se ha pintado correctamente la casilla ' . $_POST['columna'] . ' de la fila ' . $_POST['fila'];
    header('Location: index.php', true, 302);
    exit();
}

function deshacerCambio(): void
{
    if (isset($_SESSION['colores']['acciones'])) {
        $_SESSION['colores']['tablero'][$_SESSION['colores']['acciones'][count($_SESSION['colores']['acciones']) - 1]['fila'][$_SESSION['colores']['acciones'][count($_SESSION['colores']['acciones']) - 1]['columna']]] = $_SESSION['colores']['acciones'][count($_SESSION['colores']['acciones']) - 1]['color_inicial'];
    }
}