<?php

session_start();

include_once(__DIR__ . '/bd.php');

/*
 * Estructura de $_SESSION['cuestionario'] (una única clave para todo el proceso):
 * - 'preguntas'  => array de 5 IDs enteros obtenidos al azar de la BD (ej: [7, 2, 12, 4, 9])
 * - 'actual'     => índice (0 a 4) de la pregunta que toca responder ahora
 * - 'respuestas' => array asociativo [id_pregunta => 'letra_elegida'] (o indexado 0..4)
 * - 'inicio'     => time()
 *
 * (El id del usuario ya viene en $_SESSION['id'] del login; si no existe $_SESSION['cuestionario'],
 * significa que no hay ningún cuestionario en curso).
 * 
 * ESTRUCTURA FINAL:
 * 
 * $_SESSION['cuestionario'] =>
 * [
 *      'inicio' => cuando empezó el cuestionario,
 *      'preguntas' => [1,2,3,4,5],
 *      'actual' => 2,
 *      'respuestas' => [
 *          'id_pregunta' => 'letra_elegida',
 *          ...
 *      ]
 * ]
 */

function getCuestion($id): null|false|array
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare('SELECT id,enunciado, opcion_a, opcion_b, opcion_c, opcion_d FROM preguntas WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $res = $stmt->fetch();
            if ($res) {
                return $res;
            }
            return false;
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}

function calcularAciertos(): null|int
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $aciertos = 0;

            foreach ($_SESSION['cuestionario']['respuestas'] as $id => $respuesta) {
                $stmt = $pdo->prepare('SELECT correcta FROM preguntas WHERE id = :id');
                $stmt->execute(['id' => $id]);
                $resultado = $stmt->fetch(PDO::FETCH_COLUMN);

                if ($respuesta === $resultado) {
                    $aciertos++;
                }
            }

            return $aciertos;
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}

function insertarResultados()
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare('INSERT INTO resultados (usuario_id, aciertos, total_preguntas) VALUES (:usuario_id, :aciertos, :total_preguntas)');
            $stmt->execute([
                'usuario_id' => $_SESSION['id'],
                'aciertos' => $_SESSION['cuestionario']['resultados'],
                'total_preguntas' => count($_SESSION['cuestionario']['preguntas'])
            ]);

            $_SESSION['ultimo_resultado'] = $pdo->lastInsertId();
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}

if (isset($_SESSION['id'])) {
    if (!isset($_SESSION['cuestionario'])) {
        header('Location: bienvenida.php', true, 302);
        exit;
    } else {
        // SI HAY CUESTIONARIO EMPEZADO
        if ($_SESSION['cuestionario']['actual'] >= 5) {
            $_SESSION['cuestionario']['resultados'] = calcularAciertos();
            insertarResultados();
            unset($_SESSION['cuestionario']);
            header('Location: resultado.php', true, 302);
            exit;
        } else {
            $cuestion = getCuestion($_SESSION['cuestionario']['preguntas'][$_SESSION['cuestionario']['actual']]);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['abandonar'])) {
                    unset($_SESSION['cuestionario']);
                    header('Location: bienvenida.php', true, 302);
                    exit;
                } else {
                    if ($_POST['respuesta']) {
                        if (isset($_POST['pregunta_id']) && (int)$_POST['pregunta_id'] === (int)$_SESSION['cuestionario']['preguntas'][$_SESSION['cuestionario']['actual']]) {
                            $valid_options = ['a', 'b', 'c', 'd'];
                            $respuesta = $_POST['respuesta'];
                            if (in_array($respuesta, $valid_options)) {
                                $_SESSION['cuestionario']['respuestas'][$cuestion['id']] = $respuesta;
                                $_SESSION['cuestionario']['actual']++;
                                header('Location: cuestionario.php', true, 303);
                                exit;
                            }
                        } else {
                            $_SESSION['flash_messages']['cuestionario']['invalidcuestion'] = 'La pregunta no coincide con la actual';
                        }
                    }
                }
            }
        }
    }
} else {
    header('Location: login.php', true, 302);
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <?= htmlspecialchars($_SESSION['flash_messages']['cuestionario']['randomsID'] ?? '') ?>
    <?php unset($_SESSION['flash_messages']['cuestionario']['randomsID']) ?>

    <?= htmlspecialchars($_SESSION['flash_messages']['cuestionario']['invalidcuestion'] ?? '') ?>
    <?php unset($_SESSION['flash_messages']['cuestionario']['invalidcuestion']) ?>

    <form action="" method="POST">
        <h3>PREGUNTA <?= htmlspecialchars($_SESSION['cuestionario']['actual'] + 1 ?? '') ?> de 5</h3>
        <fieldset>
            <legend>PREGUNTA ACTUAL: <?= htmlspecialchars($cuestion['enunciado'] ?? '') ?></legend>

            <div>
                <input type="radio" id="opcion_a" name="respuesta" value="a" required>
                <label for="opcion_a">Opción a: <?= htmlspecialchars($cuestion['opcion_a'] ?? '') ?></label>
            </div>

            <div>
                <input type="radio" id="opcion_b" name="respuesta" value="b">
                <label for="opcion_b">Opción b: <?= htmlspecialchars($cuestion['opcion_b'] ?? '') ?></label>
            </div>

            <div>
                <input type="radio" id="opcion_c" name="respuesta" value="c">
                <label for="opcion_c">Opción c: <?= htmlspecialchars($cuestion['opcion_c'] ?? '') ?></label>
            </div>

            <div>
                <input type="radio" id="opcion_d" name="respuesta" value="d">
                <label for="opcion_d">Opción d: <?= htmlspecialchars($cuestion['opcion_d'] ?? '') ?></label>
            </div>

            <input type="hidden" name="pregunta_id" value="<?= $cuestion['id'] ?? 0 ?>">
            <button type="submit">Responder</button>
        </fieldset>
    </form>
    <form action="" method="POST">
        <button type="submit" name="abandonar" value="1">Abandonar</button>
    </form>

</body>

</html>