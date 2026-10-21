<?php

function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Coger los inputs de URL
function getInputs()
{
    $inputs = [];
    $filtrado_pista = filter_input(INPUT_GET, 'pista', FILTER_UNSAFE_RAW);
    $filtrado_fecha = filter_input(INPUT_GET, 'fecha', FILTER_UNSAFE_RAW);

    array_push($inputs, $filtrado_pista);
    array_push($inputs, $filtrado_fecha);

    return $inputs;
}

// Coger todas las reservas o las filtradas desde la BBDD
function leerReservas(array $inputs)
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

        $sqlstring = 'SELECT * FROM reservas';
        $params = [];

        if ($inputs[0] !== null && $inputs[1] === null) {
            $sqlstring .= ' WHERE pista = ?';
            $params[] = $inputs[0];
        } else if ($inputs[0] === null && $inputs[1] !== null) {
            $sqlstring .= ' WHERE date = ?';
            $params[] = $inputs[1];
        } else if ($inputs[0] !== null && $inputs[1] !== null) {
            $sqlstring .= ' WHERE pista = ? AND date = ?';
            $params[] = $inputs[0];
            $params[] = $inputs[1];
        }

        $sqlstring .= ';';

        $stmt = $pdo->prepare($sqlstring); // Esto devuelve un statement: comentarle al profe
        $stmt->execute($params); // Ejecuta la consulta con los parametros de inputs que son las interrogaciones

        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo e('<h1>Ha habido una exepción con la BBDD</h1>');
        return [];
    }
}

function obtenerReservas(array $inputs)
{
    $inputs = getInputs();
    return leerReservas($inputs);
}
