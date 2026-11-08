<!DOCTYPE html>
<html lang="es">

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
        <form action="" method="GET">
            <button type="submit" name="lista_torneos">Listar torneos</button>
        </form>

        <?php if (!empty($mensaje)): ?>
            <p><strong><?php echo e($mensaje); ?></strong></p>
        <?php endif; ?>

        <?php if (isset($torneo_edicion) && $torneo_edicion): ?>
            <section>
                <h2>Editar puntos: <?php echo e($torneo_edicion['nombre']); ?> <small><?php echo e($torneo_edicion['juego']); ?></small></h2>

                <form action="index.php?accion=actualizar_puntos&id=<?php echo e($torneo_edicion['torneo_id']); ?>" method="POST">
                    <table>
                        <thead>
                            <tr>
                                <th>Jugador</th>
                                <th>Puntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($torneo_edicion['jugadores'] as $jugador): ?>
                                <tr>
                                    <td><?php echo e($jugador['nick']); ?></td>
                                    <td>
                                        <input type="number"
                                            name="puntos[<?php echo e($jugador['jugador_id']); ?>]"
                                            value="<?php echo e($jugador['puntos']); ?>"
                                            required min="0">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit">Guardar Puntos</button>
                </form>
            </section>
        <?php endif; ?>

        <?php if (isset($tournaments)): ?>
            <?php foreach ($tournaments as $tournament): ?>
                <section>
                    <h2>
                        <?php echo e($tournament['nombre']) ?>
                        <small><?php echo e($tournament['juego']) ?></small>
                        <a href="index.php?accion=actualizar_puntos&id=<?php echo e($tournament['torneo_id']); ?>">(Editar Puntos)</a>
                    </h2>

                    <?php if (!empty($tournament['jugadores'])): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Jugador</th>
                                    <th>Puntos</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tournament['jugadores'] as $jugador): ?>
                                    <tr>
                                        <td><?php echo e($jugador['nick']) ?></td>
                                        <td><?php echo e($jugador['puntos']) ?></td>
                                        <td>
                                            <form action="" method="GET">
                                                <button type="submit" name="borrar_jugador">Borrar jugador</button>
                                                <input type="hidden" name="jugador_id" value="<?php echo e($jugador['jugador_id']); ?>">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer>
        <p>DWES - Examen 1B - Ejercicio 1</p>
    </footer>
</body>

</html>