<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nuevo libro</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
<?php /** @var array $errores */ ?>
<?php /** @var array $datosForm */ ?>
<?php /** @var int|false|null $creado */ ?>

<!-- CAMBIO: mensajes de éxito y de error dentro de la plantilla, ya no se hace echo en la función -->
<?php if ($creado): ?>
    <h2>Libro añadido con éxito (id <?php echo htmlspecialchars((string)$creado) ?>)</h2>
<?php endif; ?>

<?php foreach ($errores as $error): ?>
    <h2><?php echo htmlspecialchars($error) ?></h2>
<?php endforeach; ?>

<form method="POST" action="">
    <label>Nuevo libro</label>
    <!-- CAMBIO: se mantiene lo escrito si hay errores, y se añaden "required" y los límites de la BBDD -->
    <input type="text" name="isbn" placeholder="ISBN" maxlength="17" required
           value="<?php echo htmlspecialchars((string)($datosForm['isbn'] ?? '')) ?>">
    <input type="text" name="titulo" placeholder="Título del libro" maxlength="150" required
           value="<?php echo htmlspecialchars((string)($datosForm['titulo'] ?? '')) ?>">
    <input type="text" name="autor" placeholder="Autor del libro" maxlength="100" required
           value="<?php echo htmlspecialchars((string)($datosForm['autor'] ?? '')) ?>">
    <input type="number" name="anio" placeholder="Año del libro" min="0" max="<?php echo date('Y') ?>" required
           value="<?php echo htmlspecialchars((string)($datosForm['anio'] ?? '')) ?>">

    <!-- CAMBIO: el género es un <select> con los mismos géneros que el filtro, en vez de texto libre -->
    <select name="genero" required>
        <option value="">Género del libro</option>
        <?php foreach (GENEROS as $genero): ?>
            <option value="<?php echo htmlspecialchars($genero) ?>"
                    <?php echo (($datosForm['genero'] ?? '') === $genero) ? 'selected' : '' ?>>
                <?php echo htmlspecialchars($genero) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Añadir</button>
</form>
<a href="index.php">VER LOS LIBROS QUE HAY</a>
<a href="ficha.php">BUSCAR UN LIBRO POR ID</a>
</body>
</html>