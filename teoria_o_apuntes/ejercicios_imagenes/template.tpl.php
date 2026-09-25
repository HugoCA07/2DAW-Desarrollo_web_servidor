<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Coches con imágenes</title>

    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h1>Bienvenido a los coches más guapos de la historia</h1>

    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once('functions/functions.php');

    $coches = getCSVContentInArrayProfe('coches.csv');

    $defaultURL = 'https://static.wikia.nocookie.net/bobesponja/images/5/5d/180px-Burgermobil_%281%29.jpg/revision/latest/smart/width/250/height/250?cb=20100728151830';

    ?>

    <?php if (!empty($coches)): ?>

        <ul>

            <?php foreach ($coches as $valor): ?>

                <?php

                $imgURL = $valor['URL_IMAGEN'];

                if (empty($imgURL)) {
                    $imgURL = $defaultURL;
                }

                ?>

                <li>

                    <p>
                        <strong>Marca:</strong>
                        <?= htmlspecialchars($valor['Marca']) ?>
                    </p>

                    <p>
                        <strong>Modelo:</strong>
                        <?= htmlspecialchars($valor['Modelo']) ?>
                    </p>

                    <p>
                        <strong>Color:</strong>
                        <?=$valor['Color'] ?>
                    </p>

                    <p>
                        <strong>Matrícula:</strong>
                        <?= $valor['Matricula'] ?>
                    </p>

                    <img
                        src="<?= $imgURL ?>"
                        alt="<?= $valor['Marca'] . ' ' . $valor['Modelo'] ?>"
                        width="250">

                </li>

            <?php endforeach; ?>

        </ul>

    <?php else: ?>

        <p>No se han encontrado coches.</p>

    <?php endif; ?>

</body>

</html>