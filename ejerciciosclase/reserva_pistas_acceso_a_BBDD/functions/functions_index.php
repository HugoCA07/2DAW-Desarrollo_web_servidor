<?php

function e(string $str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Coger los inputs de URL
function getInputs()
{
    $inputs = [];
    $filtrado_pista = filter_input(INPUT_GET, 'pista', FILTER_UNSAFE_RAW);
    $filtrado_fecha = filter_input(INPUT_GET, 'fecha', FILTER_UNSAFE_RAW);

    $inputs[] = $filtrado_pista;
    $inputs[] = $filtrado_fecha;

    return $inputs;
}

// Coger todas las reservas o las filtradas desde la BBDD
function leerReservas(array $inputs): array
{
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=reservapistas;charset=utf8mb4',
            'dwes',
            'abc123.',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        $sqlstring = 'SELECT nombre, email, pista, date, hora, material, normas FROM reservas';
        $params = [];

        if (!empty($inputs[0]) && empty($inputs[1])) {
            $sqlstring .= ' WHERE pista = ?';
            $params[] = $inputs[0];
        } else if (empty($inputs[0]) && !empty($inputs[1])) {
            $sqlstring .= ' WHERE date = ?';
            $params[] = $inputs[1];
            // Han rellenado ambos campos
        } else if (!empty($inputs[0]) && !empty($inputs[1])) {
            $sqlstring .= ' WHERE pista = ? AND date = ?';
            $params[] = $inputs[0];
            $params[] = $inputs[1];
        }

        $sqlstring .= ';';

        $stmt = $pdo->prepare($sqlstring);
        $stmt->execute($params);

        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo '<h1>Ha habido una excepción con la BBDD</h1>';
        return [];
    }
}

function obtenerReservas(array $inputs): array
{
    $inputs = getInputs();
    return leerReservas($inputs);
}
