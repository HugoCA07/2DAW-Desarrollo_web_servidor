<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZELDA SESIONES</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>

<?php
/** @var array $tablero */
/** @var int $link_pos_x */
/** @var int $link_pos_y */
/** @var int $filas */
/** @var int $columnas */
?>

<h1>ZELDA CON SESIONES</h1>

<!-- Controles de Movimiento -->
<div class="movimiento">
    <form method="POST">
        <button type="submit" name="direccion" value="arriba">Arriba</button>
        <button type="submit" name="direccion" value="abajo">Abajo</button>
        <button type="submit" name="direccion" value="izquierda">Izquierda</button>
        <button type="submit" name="direccion" value="derecha">Derecha</button>
    </form>
</div>

<!-- Renderizado del Tablero -->
<div class="tablero">
    <?php foreach (($tablero ?? []) as $y => $fila): ?>
        <?php foreach ($fila as $x => $casilla): ?>

            <?php if ($_SESSION['link_pos_x'] === $x && $_SESSION['link_pos_y'] === $y): ?>
                <div class="tile tile-<?= htmlspecialchars($casilla) ?> link">
                    <img src="img/link.png" alt="Link">
                </div>
            <?php else: ?>
                <div class="tile tile-<?= htmlspecialchars($casilla) ?>"></div>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php endforeach; ?>
</div>

</body>
</html>
