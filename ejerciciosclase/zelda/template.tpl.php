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

    <div class="tablero">

        <?php /** @var array $tablero */ ?>

        <?php foreach ($tablero as $fila): ?>

            <?php foreach ($fila as $casilla): ?>

                <div class="tile tile-<?php echo $casilla; ?>"></div>

            <?php endforeach; ?>

        <?php endforeach; ?>

    </div>

</body>

</html>