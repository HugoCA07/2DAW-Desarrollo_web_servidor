<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Red Dead Redemption 2 >>>>> Cualquier Zelda</title>

    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php /** @var array $tablero */ ?>
    <?php /** @var int $link_pos_x */ ?>
    <?php /** @var int $link_pos_y */ ?>
    <?php /** @var int $filas */ ?>
    <?php /** @var int $columnas */ ?>

    <h1>RDR2 > TODO</h1>

    <div class="movimiento">
        <a href="<?php
                    if (($link_pos_y - 1) !== -1 && str_contains($tablero[$link_pos_y - 1][$link_pos_x], 'water') !== true && str_contains($tablero[$link_pos_y - 1][$link_pos_x], 'rock') !== true) {
                        echo "index.php?link_pos_x=" . $link_pos_x . "&link_pos_y=" . ($link_pos_y - 1);
                    }
                    ?>">Arriba</a>
        <a href="<?php if (($link_pos_y + 1) !== $filas && str_contains($tablero[$link_pos_y + 1][$link_pos_x], 'water') !== true && str_contains($tablero[$link_pos_y + 1][$link_pos_x], 'rock') !== true) {
                        echo "index.php?link_pos_x=" . $link_pos_x . "&link_pos_y=" . ($link_pos_y + 1);
                    } ?>">Abajo</a>
        <a href="<?php if (($link_pos_x - 1) !== -1 && str_contains($tablero[$link_pos_y][$link_pos_x - 1], 'water') !== true && str_contains($tablero[$link_pos_y][$link_pos_x - 1], 'rock') !== true) {
                        echo "index.php?link_pos_x=" . ($link_pos_x - 1) . "&link_pos_y=" . $link_pos_y;
                    } ?>">Izquierda</a>
        <a href="<?php if (($link_pos_x + 1) !== $columnas && str_contains($tablero[$link_pos_y][$link_pos_x + 1], 'water') !== true && str_contains($tablero[$link_pos_y][$link_pos_x + 1], 'rock') !== true) {
                        echo "index.php?link_pos_x=" . ($link_pos_x + 1) . "&link_pos_y=" . $link_pos_y;
                    } ?>">Derecha</a>
    </div>


    <div class="tablero">

        <!-- Comprobación para que no te diga de que la variable de que tablero no existía (He tenido que buscarlo) -->
        <?php foreach (($tablero ?? []) as $fila): ?>

            <?php foreach ($fila as $casilla): ?>


                <?php
                if ($link_pos_x === $contador_columnas && $link_pos_y === $contador_filas) {
                    echo
                    '<div class="tile tile-' . $casilla . ' link">
                        <img src="img/link.png" alt="Link">
                    </div>';
                } else {
                    echo '<div class="tile tile-' . $casilla . '"></div>';
                }

                ?>

                <?php $contador_columnas++; ?>

            <?php endforeach; ?>

            <?php $contador_columnas = 0; ?>
            <?php $contador_filas++; ?>

        <?php endforeach; ?>

    </div>

</body>

</html>