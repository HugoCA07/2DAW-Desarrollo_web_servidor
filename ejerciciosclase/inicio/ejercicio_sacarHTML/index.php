<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../ejercicio_csv_toArray/src/functions.php');

$coches = getCSVContentInArray('../ejercicio_csv_toArray/coches.csv', "Marca");

$salida = getMarkUpFromDataCars($coches);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJEMPLO</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>EJEMPLO DE GENERACION DE CÓDIGO</h1>
    <?php echo ($salida??''); ?>
</body>
</html>