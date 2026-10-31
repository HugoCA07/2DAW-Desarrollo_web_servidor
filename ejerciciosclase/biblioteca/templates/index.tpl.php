<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INDEX</title>
</head>
<body>
    <?php /** @var array $libros */ ?>
    <form method="GET" action="">
        <label>
            <select name="genero">
                <option value="Todos">Todos</option>
                <option value="Novela">Novela</option>
                <option value="Teatro">Teatro</option>
                <option value="Ensayo">Ensayo</option>
                <option value="Poesía">Poesía</option>
                <option value="Cómic">Cómic</option>
            </select>
        </label>
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <?php if ($libros): ?>
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Año</th>
            <th>Genero</th>
            <th>Estado</th>
        </tr>
            <?php foreach ($libros as $libro) : ?>
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
            <?php endforeach; ?>
        <?php else: ?>
            <h1>No hay libros de ese genero</h1>
        <?php endif; ?>
    </table>
    <a href="<?php header('Location: nuevo.php', true, 303); ?>">AÑADIR UN LIBRO</a>
</body>
</html>