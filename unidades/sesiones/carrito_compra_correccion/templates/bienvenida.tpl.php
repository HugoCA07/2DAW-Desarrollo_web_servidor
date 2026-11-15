<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h2>¡Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>!</h2>

    <?php if (!empty($_SESSION['flash_messages']['confirmacion'])): ?>
        <p><strong><?= htmlspecialchars($_SESSION['flash_messages']['confirmacion']) ?></strong></p>
        <?php unset($_SESSION['flash_messages']['confirmacion']); ?>
    <?php endif; ?>

    <?php if (!empty($pedidos) && is_array($pedidos)): ?>
        <table>
            <thead>
                <tr>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= number_format((float)$pedido['total'], 2, ',', '.') ?> €</td>
                        <td><?= htmlspecialchars($pedido['fecha_espanol']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>Todavía no tienes pedidos</h2>
    <?php endif; ?>

    <a href="tienda.php">Tienda</a>
    <a href="carrito.php">Tu carrito</a>
    <a href="logout.php">Logout</a>
</body>

</html>