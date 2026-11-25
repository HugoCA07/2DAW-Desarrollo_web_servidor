<?php
const FILAS = 6;
const COLUMNAS = 8;

const TILES = [
    'hierba' => ['nombre' => 'Hierba', 'max' => 12],
    'agua' => ['nombre' => 'Agua', 'max' => 8],
    'arena' => ['nombre' => 'Arena', 'max' => 8],
    'arbol' => ['nombre' => 'Árbol', 'max' => 6],
    'roca' => ['nombre' => 'Roca', 'max' => 4],
    'muro' => ['nombre' => 'Muro', 'max' => 10],
];

function dump(mixed $var): void
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function inicializarTablero(): void
{
    $_SESSION['tablero'] = [];

    for ($i = 1; $i <= FILAS; $i++) {
        for ($j = 1; $j <= COLUMNAS; $j++) {
            $_SESSION['tablero'][$i][$j] = null;
        }
    }

    $_SESSION['contador'] = [];

    foreach (TILES as $tipo => $caracteristicas) {
        $_SESSION['contador'][$tipo] = 0;
    }
}

function validarFila(int $fila): bool
{
    if(!filter_var($fila, FILTER_VALIDATE_INT) || $fila > FILAS || strlen($fila) <= 0) {
        return false;
    } else {
        return true;
    }
}

function validarColumna(int $columna): bool
{
    if(!filter_var($columna, FILTER_VALIDATE_INT) || $columna > COLUMNAS || strlen($columna) <= 0) {
        return false;
    } else {
        return true;
    }
}

function validarTile(string $tile): bool
{
    if (array_key_exists($tile, TILES)) {
        return true;
    } else {
        return false;
    }
}

function validarCantidadTile(string $tile): bool
{
    if (isset($_SESSION['contador'][$tile]) && $_SESSION['contador'][$tile] + 1 <= TILES[$tile]['max']) {
        return true;
    } else {
        return false;
    }
}

function validarFilasColumnasDuplicado(): array
{
    $isValidFila = validarFila($_POST['fila']);

    if ($isValidFila) {
        $_SESSION['fila'] = $_POST['fila'];
    } else {
        $_SESSION['flash_messages']['error']['fila'] = 'La fila debe de ser un numero entre 1 y ' . FILAS;
    }

    $isValidColumna = validarColumna($_POST['columna']);

    if ($isValidColumna) {
        $_SESSION['columna'] = $_POST['columna'];
    } else {
        $_SESSION['flash_messages']['error']['column'] = 'La columna debe de ser un numero entre 1 y ' . COLUMNAS;
    }
    return array($isValidFila, $isValidColumna);
}

function printSuccessMesagges(): void
{
    if (isset($_SESSION['flash_messages']['success'])) {
        echo '<div class="mensaje ok">' . $_SESSION['flash_messages']['success'] . '</div>';
        unset($_SESSION['flash_messages']['success']);
    }
}

function printErrorMesages(): void
{
    if (isset($_SESSION['flash_messages']['error'])) {
        echo '<div class="mensaje error">' . $_SESSION['flash_messages']['error'] . '</div>';
        unset($_SESSION['flash_messages']['error']);
    }
}