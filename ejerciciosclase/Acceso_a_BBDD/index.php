<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dwes;charset=utf8mb4',
        'dwes',
        'abc123.',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $cod_fam = filter_input(INPUT_GET, 'cod');

    if ($cod_fam === null || $cod_fam === false) {
        $sqlstring = 'SELECT * from familia;';
    } else {
        $sqlstring = "SELECT cod, nombre from familia WHERE cod='" . $cod_fam . "';";
    }

    $resultado = $pdo->query($sqlstring);

    if ($resultado) {
        $data = $resultado->fetchall();
        echo '<ul>';
        foreach ($data as $key => $value) {
            echo '<li>';

            if (!isset($value['cod']) || $value['cod'] === null) {
                echo $value['nombre'];
            } else {
                echo $value['cod'] . ' => ' . $value['nombre'];
            }
            echo '</li>';
        }
        echo '</ul>';
    }
} catch (Exception $e) {
    echo 'Se lanzo una excepción: ' . $e->getMessage();
}
