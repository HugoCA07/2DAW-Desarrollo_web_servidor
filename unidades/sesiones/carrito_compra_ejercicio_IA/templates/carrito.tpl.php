<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu carrito</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <?php if (!empty($_SESSION['carrito'])): ?>
        <?php $total = 0; ?>

        <h2><?= htmlspecialchars($_SESSION['flash_messages']['sinstock'] ?? '') ?></h2>
        <?php unset($_SESSION['flash_messages']['sinstock']) ?>

        <h2><?= htmlspecialchars($_SESSION['flash_messages']['confirmacion'] ?? '') ?></h2>
        <?php unset($_SESSION['flash_messages']['confirmacion']) ?>

        <?php if (isset($productos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= htmlspecialchars($producto['precio']) ?></td>
                            <td><?= htmlspecialchars($_SESSION['carrito'][$producto['id']]) ?></td>
                            <td><?= htmlspecialchars((float) $producto['precio'] * (int) $_SESSION['carrito'][$producto['id']]) ?></td>
                            <td>
                                <form action="" method="POST">
                                    <button type="submit" name="accion" value="sumar">Añadir 1</button>
                                    <button type="submit" name="accion" value="restar">Eliminar 1</button>
                                    <button type="submit" name="accion" value="eliminar">Eliminar articulo</button>
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
                                </form>
                            </td>

                            <?php $total += (float) $producto['precio'] * (int) $_SESSION['carrito'][$producto['id']] ?>
                        </tr>
                    <?php endforeach; ?>

                    <?= htmlspecialchars($_SESSION['flash_messages']['sumar'] ?? '') ?>
                    <?php unset($_SESSION['flash_messages']['sumar']) ?>
                </tbody>
            <?php endif; ?>
            </table>
            <h4>El total de tu carrito es: <?= htmlspecialchars($total) ?></h4>
            <form action="" method="POST">
                <button type="submit" name="accion" value="comprar">Confirmar la compra</button>
                <button type="submit" name="accion" value="vaciar_carro">Vaciar el carrito</button>
                <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">
            </form>
        <?php else: ?>
            <h2>Su carrito está vacio</h2>
        <?php endif; ?>

        <a href="tienda.php">Volver a la tienda</a>
        <a href="bienvenida.php">Página principal</a>
        <a href="logout.php">Logout</a>
</body>

</html>