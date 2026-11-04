<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buscar Libro</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
<?php /** @var array|false $libro */ ?>
<?php /** @var bool $buscado */ ?>
<form method="GET" action="">
    <label>Buscar libro</label>
    <label>
        <input type="number" name="id" placeholder="ID" min="1" required>
        <button type="submit">Buscar</button>
    </label>
</form>

<?php if ($libro): ?>
    <table>
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
            <td><?php echo htmlspecialchars((string)$libro['anio']) ?></td>
            <td><?php echo htmlspecialchars($libro['genero']) ?></td>
            <td><?php echo ($libro['disponible'] === 1) ? "Disponible" : "Prestado" ?></td>
        </tr>
    </table>

    <form method="POST" action="accion.php">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$libro['id']); ?>">
        <?php if ($libro['disponible'] === 1): ?>
            <button type="submit" name="accion" value="prestar">Prestar</button>
        <?php else: ?>
            <button type="submit" name="accion" value="devolver">Devolver</button>
        <?php endif; ?>
    </form>
<?php elseif ($buscado): ?>
    <!-- CAMBIO: solo sale "no encontrado" si el usuario ha buscado algo. Antes salía siempre al entrar,
         y estaba dentro del <table> -->
    <h1>Libro no encontrado</h1>
<?php endif; ?>

<a href="nuevo.php">AÑADIR UN LIBRO</a>
<a href="index.php">VER LOS LIBROS</a>
</body>
</html>