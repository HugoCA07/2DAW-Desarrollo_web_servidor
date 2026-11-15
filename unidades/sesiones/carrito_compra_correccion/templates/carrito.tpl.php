<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu carrito</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <?php if (!empty($_SESSION['flash_messages']['sinstock'])): ?>
        <h2><?= htmlspecialchars($_SESSION['flash_messages']['sinstock']) ?></h2>
        <?php unset($_SESSION['flash_messages']['sinstock']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_messages']['sumar'])): ?>
        <p><strong><?= htmlspecialchars($_SESSION['flash_messages']['sumar']) ?></strong></p>
        <?php unset($_SESSION['flash_messages']['sumar']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['carrito']) && !empty($productos)): ?>
        <?php $total = 0.0; ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <?php
                    $cant = (int)$_SESSION['carrito'][$producto['id']];
                    $subtotal = (float)$producto['precio'] * $cant;
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= number_format((float)$producto['precio'], 2, ',', '.') ?> €</td>
                        <td><?= $cant ?></td>
                        <td><?= number_format($subtotal, 2, ',', '.') ?> €</td>
                        <td>
                            <form action="" method="POST">
                                <button type="submit" name="accion" value="sumar">Añadir 1</button>
                                <button type="submit" name="accion" value="restar">Eliminar 1</button>
                                <button type="submit" name="accion" value="eliminar">Eliminar artículo</button>
                                <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>El total de tu carrito es: <?= number_format($total, 2, ',', '.') ?> €</h4>
        <form action="" method="POST">
            <button type="submit" name="accion" value="comprar">Confirmar la compra</button>
            <button type="submit" name="accion" value="vaciar_carro">Vaciar el carrito</button>
        </form>
    <?php else: ?>
        <h2>Su carrito está vacío</h2>
    <?php endif; ?>

    <a href="tienda.php">Volver a la tienda</a>
    <a href="bienvenida.php">Página principal</a>
    <a href="logout.php">Logout</a>
</body>

</html>