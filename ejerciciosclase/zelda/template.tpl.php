<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Red Dead Redemption 2 >>>>> Cualquier Zelda</title>

    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h1>RDR2 > TODO</h1>

    <div class="movimiento">
        <a href="">Arriba</a>
        <a href="">Izquierda</a>
        <a href="">Derecha</a>
        <a href="">Abajo</a>
    </div>

    <div class="tablero">

        <?php /** @var array $tablero */ ?>
        <?php /** @var int $link_pos_x */ ?>
        <?php /** @var int $link_pos_y */ ?>

        <?php foreach ($tablero as $fila): ?>

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