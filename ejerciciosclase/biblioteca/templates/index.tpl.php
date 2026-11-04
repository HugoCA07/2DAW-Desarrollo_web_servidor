<!doctype html>
<!-- CAMBIO: lang="es" porque la web está en español -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- CAMBIO: quitado user-scalable=no y maximum-scale (perjudican la accesibilidad) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LIBROS</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
<?php /** @var array $libros */ ?>
<?php /** @var int $disponibles */ ?>
<?php /** @var string $generoActual */ ?>

<!-- CAMBIO: el contador ahora está dentro del <body>, ya no sale antes del doctype -->
<h2>LIBROS DISPONIBLES: <?php echo $disponibles ?></h2>

<form method="GET" action="">
    <label>
        <select name="genero">
            <option value="Todos">Todos</option>
            <!-- CAMBIO: los géneros salen de la constante GENEROS y se mantiene el elegido -->
            <?php foreach (GENEROS as $genero): ?>
                <option value="<?php echo htmlspecialchars($genero) ?>"
                        <?php echo ($genero === $generoActual) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($genero) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Filtrar</button>
</form>

<?php if ($libros): ?>
    <table>
        <tr>
            <!-- CAMBIO: se muestra el ID para poder usarlo en "buscar por ID" -->
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Año</th>
            <th>Genero</th>
            <th>Estado</th>
            <th>Borrar</th>
        </tr>
        <?php foreach ($libros as $libro) : ?>
            <tr>
                <td><?php echo htmlspecialchars((string)$libro['id']) ?></td>
                <td><?php echo htmlspecialchars($libro['titulo']) ?></td>
                <td><?php echo htmlspecialchars($libro['autor']) ?></td>
                <td><?php echo htmlspecialchars((string)$libro['anio']) ?></td>
                <td><?php echo htmlspecialchars($libro['genero']) ?></td>
                <!-- CAMBIO: sin htmlspecialchars, son textos fijos -->
                <td><?php echo ($libro['disponible'] === 1) ? "Disponible" : "Prestado" ?></td>
                <td>
                    <form method="POST" action="accion.php">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$libro['id']); ?>">

                        <button type="submit" name="borrar">Borrar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <!-- CAMBIO: el <h1> estaba dentro del <table>, y eso es HTML inválido -->
    <h1>No hay libros de ese genero</h1>
<?php endif; ?>

<a href="nuevo.php">AÑADIR UN LIBRO</a>
<a href="ficha.php">BUSCAR UN LIBRO POR ID</a>
</body>
</html>