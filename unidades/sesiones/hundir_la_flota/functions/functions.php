<?php

function validCoordenada(int $row, int $celda): bool
{
    if ($row < 0 || $row > 3) {
        return false;
    } else if ($celda < 0 || $celda > 3) {
        return false;
    }

    if ($_SESSION['flota']['tablero'][$row][$celda] >= 2) {
        return false;
    }

    return true;
}

function comprobarTurnos(int $turnos): bool
{
    if ($turnos <= 6 && $turnos > 0) {
        return true;
    } else {
        return false;
    }
}
