<?php

function dump(mixed $var): void
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function obtenercasilla(string $location): array
{
    $casilla = explode("-", $location);
    $casilla = [
        'fila' => (int)$casilla[0],
        'columna' => (int)$casilla[1]
    ];
    return $casilla;
}

function validarCasilla(array $casilla)
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
}

// TODO: REHACER
function comprobarVictoria(array $tablero)
{
    if (!isset($tablero) || empty($tablero)) {
        return false;
    }

    // VER SI ALGUNA FILA CONSIGUIÓ LAS 3 SEGUIDAS
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

    // VER SI ALGUNA COLUMNA CONSIGUIÓ LAS 3 SEGUIDAS
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
