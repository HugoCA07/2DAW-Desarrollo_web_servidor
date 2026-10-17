<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videojuegos</title>
</head>

<body>
    <h1>Videojuegos con Stock</h1>
    <?php if (!empty($videojuegos)): ?>
        <ul>
            <?php foreach ($videojuegos as $valor): ?>
                <?php
                $imgURL = !empty($valor['URL_Portada']) ? $valor['URL_Portada'] : $defaultURL;
                ?>
                <li>
                    <p>
                        <strong>Titulo:</strong>
                        <?= $valor['Titulo'] ?? '' ?>
                    </p>
                    <p>
                        <strong>Plataforma:</strong>
                        <?= $valor['Plataforma'] ?? '' ?>
                    </p>
                    <p>
                        <strong>Precio:</strong>
                        <?= $valor['Precio'] ?? '' ?>
                    </p>
                    <p>
                        <strong>Stock:</strong>
                        <?= $valor['Stock'] ?? '' ?>
                    </p>
                    <img src="$imgURL" alt="foto del videojuego">
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay juegos con stock</p>
    <?php endif; ?>
</body>

</html>