<!doctype html>
<!-- NUEVA: plantilla para accion.php. Antes esa página no tenía ni <html> ni <body> -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultado</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
<?php /** @var string $mensaje */ ?>
<?php /** @var bool $esError */ ?>
<h2><?php echo htmlspecialchars($mensaje) ?></h2>

<?php if ($esError): ?>
    <p><em>No se ha realizado ningún cambio.</em></p>
<?php endif; ?>

<a href="index.php">Volver al listado</a>
<a href="ficha.php">BUSCAR UN LIBRO POR ID</a>
</body>
</html>