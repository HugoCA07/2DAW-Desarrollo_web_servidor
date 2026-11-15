<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h2>¡Bienvenido!</h2>
    <?php if (isset($pedidos)): ?>
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
                        <td><?= htmlspecialchars($pedido['total']) ?></td>
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