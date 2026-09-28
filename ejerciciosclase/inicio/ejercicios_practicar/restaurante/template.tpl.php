<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Restantes del CSV</title>
</head>

<body>
    <h1>PEDIDOS RESTANTES DEL RESTAURANTE</h1>

    <?php if (!empty($pedidos)): ?>
        <ul>
            <?php foreach ($pedidos as $valor): ?>
                <li>
                    <p>
                        <strong>ID Pedido</strong>
                        <?= htmlspecialchars($valor['ID_Pedido'] ?? '') ?>
                    </p>
                    <p>
                        <strong>Cliente</strong>
                        <?= htmlspecialchars($valor['Cliente'] ?? '') ?>
                    </p>
                    <p>
                        <strong>Plato</strong>
                        <?= htmlspecialchars($valor['Plato'] ?? '') ?>
                    </p>
                    <p>
                        <strong>Precio Base:</strong>
                        <?= number_format((float)($valor['Precio_Base'] ?? 0), 2, ',', '.') ?> €
                    </p>
                    <p>
                        <strong>Descuento:</strong>
                        <?= htmlspecialchars($valor['Descuento_Porcentaje'] ?? '0') ?>%
                    </p>
                    <p>
                        <strong>Estado</strong>
                        <?= htmlspecialchars($valor['Estado'] ?? '') ?>
                    </p>
                    <p>
                        <strong>Precio Final:</strong>
                        <?= number_format((float)($valor['Precio_Final'] ?? 0), 2, ',', '.') ?> €
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay pedidos pendientes.</p>
    <?php endif; ?>
</body>

</html>