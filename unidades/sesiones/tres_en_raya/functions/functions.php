<?php

function dump(mixed $var): void
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function obtenercasilla(string $location): array
{
    $casilla = explode("-", $location);
    return [
        'fila' => (int)$casilla[0],
        'columna' => (int)$casilla[1]
    ];
}

function validarCasilla(array $casilla): bool
{
    if (!empty($casilla)) {
        if ($casilla['fila'] > 3) {
            return false;
        } else if ($casilla['fila'] < 1) {
            return false;
        } else {
            if ($casilla['columna'] > 3) {
                return false;
            } else if ($casilla['columna'] < 1) {
                return false;
            } else {
                return true;
            }
        }
    }

    return false;
}

function comprobarHorizontales(array $tablero): bool
{
    foreach ($tablero as $fila) {
        $contadorCasilla = 0;
        foreach ($fila as $casilla) {
            if ($casilla === $_SESSION['partida']['turno']) {
                $contadorCasilla++;
                if ($contadorCasilla === 3) {
                    return true;
                }
            } else {
                $contadorCasilla = 0;
            }
        }
    }

    return false;
}

function comprobarVertical(array $tablero): bool
{
    for ($col = 1; $col <= 3; $col++) {
        if (
            $tablero[1][$col] !== '' &&
            $tablero[1][$col] === $tablero[2][$col] &&
            $tablero[2][$col] === $tablero[3][$col]
        ) {
            return true;
        }
    }
    return false;
}

function comprobarDiagonal(array $tablero): bool
{
    if (
        $tablero[1][1] === $_SESSION['partida']['turno'] &&
        $tablero[2][2] === $_SESSION['partida']['turno'] &&
        $tablero[3][3] === $_SESSION['partida']['turno']
    ) {
        return true;
    }

    if (
        $tablero[1][3] === $_SESSION['partida']['turno'] &&
        $tablero[2][2] === $_SESSION['partida']['turno'] &&
        $tablero[3][1] === $_SESSION['partida']['turno']
    ) {
        return true;
    }


    return false;
}

function comprobarVictoria(array $tablero): bool
{
    if (empty($tablero)) {
        return false;
    }

    if (comprobarHorizontales($tablero) || comprobarVertical($tablero) || comprobarDiagonal($tablero)) {
        return true;
    }

    return false;
}

