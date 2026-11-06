<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneos de eSports</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
<?php /** @var array $tournaments */ ?>
<?php /** @var bool $isUpdated */ ?>
<?php /** @var bool $isDeleted */ ?>
<header>
    <h1>Torneos de eSports</h1>
    <p>Clasificación de los jugadores inscritos en cada torneo</p>
</header>

<main>
    <form action="" method="GET">
        <button type="submit" name="lista_torneos">Listar torneos</button>
    </form>

    <form action="" method="POST">
        <label>
            <input type="number" name="id" min="0" placeholder="ID Torneo" required>
        </label>
        <button type="submit" name="actualizar_puntos">Actualizar puntos</button>
    </form>

    <!-- PERDÓN POR ESTA BASOCIA DE IMPRESIÓN -->
    <?php if (isset($tournaments)): ?>
    <?php foreach ($tournaments as $tournament): ?>
        <section>
            <h2><?php echo e($tournament['nombre']) ?> <small><?php echo e($tournament['juego']) ?></small></h2>
            <table>
                <thead>
                <tr>
                    <th>Jugador</th>
                    <th>Puntos</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo e($tournament['nick']) ?></td>
                    <td><?php echo e($tournament['puntos']) ?></td>
                    <td>
                        <form action="" method="GET">
                            <button type="submit" name="borrar_jugador">Borrar jugador</button>
                            <input type="hidden" name="id" value="<?php echo $tournament['jugador_id']; ?>">
                        </form>
                    </td>
                </tr>
                <?php if (isset($mensaje)){
                    echo e($mensaje);
                }
                ?>
                </tbody>
            </table>
        </section>
    <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer>
    <p>DWES - Examen 1B - Ejercicio 1</p>
</footer>
</body>
</html>
