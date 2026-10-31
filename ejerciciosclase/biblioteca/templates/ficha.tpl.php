<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buscar Libro</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
<?php /** @var array $libro */ ?>
<form method="GET" action="">
    <label>Buscar libro</label>
    <label>
        <input type="number" name="id" placeholder="ID">
        <button type="submit">Buscar</button>
    </label>
</form>
<table>
    <?php if (isset($libro)): ?>
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Año</th>
            <th>Genero</th>
            <th>Estado</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($libro['titulo']) ?></td>
            <td><?php echo htmlspecialchars($libro['autor']) ?></td>
            <td><?php echo htmlspecialchars($libro['anio']) ?></td>
            <td><?php echo htmlspecialchars($libro['genero']) ?></td>
            <td><?php echo ($libro['disponible'] === 1) ?
                        htmlspecialchars("Disponible") :
                        htmlspecialchars("Prestado") ?>
            </td>
        </tr>
    <?php else: ?>
        <h1>Libro no encontrado</h1>
    <?php endif; ?>
</table>

<?php if (isset($libro)): ?>
<form method="POST" action="accion.php">

    <input type="hidden" name="id" value="<?php echo htmlspecialchars($libro['id']); ?>">
    <?php if ($libro['disponible'] === 1): ?>
        <button type="submit" name="accion" value="prestar">Prestar</button>
    <?php else: ?>
        <button type="submit" name="accion" value="devolver">Devolver</button>
    <?php endif; ?>
</form>
<?php endif; ?>

<a href="nuevo.php">AÑADIR UN LIBRO</a>
<a href="index.php">VER LOS LIBROS</a>
</body>
</html>