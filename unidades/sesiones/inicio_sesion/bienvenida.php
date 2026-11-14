<?php

session_start();

include_once(__DIR__ . '/bd.php');

function buscarUsuario($id): null|bool|array
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare('SELECT nombre FROM usuarios WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}

if (isset($_SESSION['id'])) {
    $usuario = buscarUsuario($_SESSION['id']);
} else {
    header('Location: login.php', true, 302);
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h1>Bienvenido, <?= htmlspecialchars($usuario['nombre'] ?? '') ?></h1>
    <a href="logout.php">Logout</a>
</body>

</html>