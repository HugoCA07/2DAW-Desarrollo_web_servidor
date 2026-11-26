<?php

const FILAS = 5;
const COLUMNAS = 5;
const TIPOS = ['carretera' => 'Carretera', 'acera' => 'Acera', 'coche' => 'Coche', 'policia' => 'Policia'];

function iniciarMapa()
{
    $_SESSION['mapa'] = [];

    for ($i = 1; $i <= FILAS; $i++) {
        for ($j = 1; $j <= COLUMNAS; $j++) {
            $_SESSION['mapa'][$i][$j] = null;
        }
    }
}

function dump(mixed $var)
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function isValidRow(mixed $row)
{
    if (filter_var($row, FILTER_VALIDATE_INT) === false) {
        return false;
    }

    if ($row > FILAS || $row <= 0) {
        return false;
    }

    return true;
}

function isValidColumn(mixed $column)
{
    if (filter_var($column, FILTER_VALIDATE_INT) === false) {
        return false;
    }

    if ($column > COLUMNAS || $column <= 0) {
        return false;
    }

    return true;
}

function isValidCeil(mixed $ceil)
{
    if (!array_key_exists($ceil, TIPOS)) {
        return false;
    }

    return true;
}
