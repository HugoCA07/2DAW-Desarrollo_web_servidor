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

function getRandomIds(): null|false|array
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare('SELECT id FROM preguntas ORDER BY RAND() LIMIT 5');
            $stmt->execute();
            $randomIDs = $stmt->fetchAll(PDO::FETCH_COLUMN); // En vez de darte varios arrays de datos solo te da uno con todas las columnas
            if ($randomIDs) {
                return $randomIDs;
            }
            return false;
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}

function getResults(): null|false|array
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha_espanol  FROM resultados WHERE usuario_id = :usuario_id ORDER BY fecha DESC");
            $stmt->execute(['usuario_id' => $_SESSION['id']]);
            $resultados = $stmt->fetchAll();
            if ($resultados) {
                return $resultados;
            }
            return false;
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cuestionario'])) {

        $randomIDs = getRandomIds();

        if ($randomIDs && count($randomIDs) === 5) {
            $_SESSION['cuestionario']['inicio'] = time();
            $_SESSION['cuestionario']['preguntas'] = $randomIDs;
            $_SESSION['cuestionario']['actual'] = 0;
            header('Location: cuestionario.php', true, 302);
            exit;
        } else {
            $_SESSION['flash_messages']['cuestionario']['randomsID'] = 'No se han podido generar los IDsRandoms';
        }
    } else {
        header('Location: cuestionario.php', true, 302);
        exit;
    }
}

$results = getResults();
$bestresult = 0;

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

    <form action="" method="POST">
        <button type="submit">
            <?= htmlspecialchars(isset($_SESSION['cuestionario']) ? 'Continuar cuestionario' : 'Empezar cuestionario') ?>
            <?php unset($_SESSION['flash_messages']['cuestionario']['boton']) ?>
        </button>
    </form>

    <a href="logout.php">Logout</a>

    <h2>Tus resultados anteriores</h2>

    <?php if (!empty($results) && is_array($results)): ?>
        <table>
            <thead>
                <tr>
                    <th>Aciertos</th>
                    <th>Preguntas respondidas</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <?php if ($result['aciertos'] > $bestresult): ?>
                        <?php $bestresult = $result['aciertos'] ?>
                    <?php endif ?>
                    <tr>
                        <td><?= htmlspecialchars($result['aciertos']) ?></td>
                        <td><?= htmlspecialchars($result['total_preguntas']) ?></td>
                        <td><?= htmlspecialchars($result['fecha_espanol']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <legend>Tu mejor resultado han sido <?= htmlspecialchars($bestresult) ?> aciertos</legend>
        </table>
    <?php else: ?>
        <p>No tienes ningún resultado todavía.</p>
    <?php endif; ?>

</body>

</html>