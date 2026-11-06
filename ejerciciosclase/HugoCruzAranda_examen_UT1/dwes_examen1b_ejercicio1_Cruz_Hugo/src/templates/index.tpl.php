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
<header>
    <h1>Torneos de eSports</h1>
    <p>Clasificación de los jugadores inscritos en cada torneo</p>
</header>

<main>

    <?php if ($tournaments): ?>
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
                    </tr>
                    </tbody>
                </table>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <h2>No hay ningún torneo</h2>
    <?php endif; ?>
</main>

<footer>
    <p>DWES - Examen 1B - Ejercicio 1</p>
</footer>
</body>
</html>
