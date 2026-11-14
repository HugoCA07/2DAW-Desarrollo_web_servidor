<?php

session_start();

include_once(__DIR__ . '/bd.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php', true, 302);
    exit;
}

if (!isset($_SESSION['ultimo_resultado'])) {
    header('Location: bienvenida.php', true, 302);
    exit;
}

$pdo = conexion();
$resultado = null;

if ($pdo !== null) {
    $stmt = $pdo->prepare(
        'SELECT aciertos, total_preguntas 
         FROM resultados 
         WHERE id = :id AND usuario_id = :usuario_id'
    );
    $stmt->execute([
        'id' => $_SESSION['ultimo_resultado'],
        'usuario_id' => $_SESSION['id']
    ]);
    $resultado = $stmt->fetch();
}

if (!$resultado) {
    header('Location: bienvenida.php', true, 302);
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h2>Estos son tus resultados del test</h2>

    <p>Has acertado: <?= (int)$resultado['aciertos'] ?> de <?= (int)$resultado['total_preguntas'] ?> preguntas</p>

    <a href="bienvenida.php">Volver a la bienvenida</a>
</body>

</html>