<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <?= $_SESSION['flash_messages']['producto'] ?? '' ?>
    <?php unset($_SESSION['flash_messages']['producto']) ?>

    <?php if (!empty($productos)): ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['precio']) ?></td>
                        <td><?= $producto['stock'] > 0 ? htmlspecialchars($producto['stock']) : 'Agotado' ?></td>
                        <td>
                            <form action="" method="POST">
                                <?php if ($producto['stock'] > 0): ?>
                                    <button type="submit">Añadir</button>
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
                                <?php else: ?>
                                    <button type="submit" disabled>Añadir</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>No hay productos disponibles</h2>
    <?php endif; ?>

    <a href="carrito.php">Ir al carrito</a>
    <a href="bienvenida.php">Página principal</a>
    <a href="logout.php">Logout</a>
</body>

</html>