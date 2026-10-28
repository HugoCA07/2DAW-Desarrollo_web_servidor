<?php

function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function getHashFromURL()
{
    return filter_input(INPUT_GET, 'hashreserva', FILTER_UNSAFE_RAW);
}

function generarHashReserva($fecha, $hora, $pista): string
{
    return hash("murmur3a", ($fecha . '_' . $hora . '_' . $pista));
}

function getDatosFromReserva($credBBDD): array
{
    $hash_buscar = getHashFromURL();
    $resultado = [];

    $pdo = new PDO(
        $credBBDD['dsn'],
        $credBBDD['username'],
        $credBBDD['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $sqlstring = 'SELECT date, hora, pista FROM reservas;';

    $stmt = $pdo->prepare($sqlstring);
    $stmt->execute();
    $valores_consulta = $stmt->fetchAll();

    foreach ($valores_consulta as $valor) {
        $horaFormateada = str_replace('.', ':', $valor['hora']);
        $horaFormateada = substr($horaFormateada, 0, 5);

        $hashreserva2 = generarHashReserva($valor['date'], $horaFormateada, $valor['pista']);
        if ($hashreserva2 === $hash_buscar) {
            $sqlstring = 'SELECT nombre, email, pista, date, hora, material, normas FROM reservas WHERE date = ? AND hora = ?;';
            $stmt = $pdo->prepare($sqlstring);
            $stmt->execute([$valor['date'], $valor['hora']]);
            $resultado = $stmt->fetch();
            echo '<pre>' . print_r($resultado, true) . '</pre>';
        }
    }

    return $resultado;
}