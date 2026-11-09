<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneos de eSports</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <header>
        <h1>Torneos de eSports</h1>
        <p>Clasificación de los jugadores inscritos en cada torneo</p>
    </header>
    <main>
        <?php if (isset($error_msg)): ?>
            <?php echo e($error_msg) ?>
        <?php endif ?>

        <form action="" method="GET">
            <button type="submit" name="op" value="listar_torneos">Listar torneos</button>
        </form>

        <?php if (isset($tournaments) && $tournaments !== null): ?>
            <?php foreach ($tournaments as $tournament): ?>
                <section>
                    <h2><?php echo e($tournament['nombre']) . ' - ' . e($tournament['juego']) ?></h2>
                    <form action="" method="POST">
                        <button type="submit" name="op" value="actualizar_puntos">Actualizar puntos</button>
                        <input type="hidden" name="id" value="<?php echo e($tournament['torneo_id']) ?>">

                        <table>
                            <thead>
                                <tr>
                                    <td>Jugador</td>
                                    <td>Puntos</td>
                                    <td>Accion</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tournament['jugadores'] as $jugador): ?>
                                    <tr>
                                        <td><?php echo e($jugador['nick']) ?></td>
                                        <td>
                                            <input type="number" name="puntos[<?php echo e($jugador['id_jugador']) ?>]" value="<?php echo e($jugador['puntos']) ?>" min="0" required>
                                        </td>
                                        <td>
                                            <a href="index.php?op=borrar_jugador&id=<?php echo e($jugador['id_jugador']) ?>">Borrar</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </form>
                </section>
            <?php endforeach ?>
        <?php endif ?>
    </main>
</body>

</html>