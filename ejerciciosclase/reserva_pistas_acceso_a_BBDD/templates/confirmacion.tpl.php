<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva confirmada</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <!-- CORRECCIÓN: Renderizamos la información dependiendo de si $reserva existe o está vacía -->
    <?php if (empty($reserva)): ?>
        <h2>No se ha encontrado la reserva (Error 404)</h2>
    <?php else: ?>
        <h1>Tu reserva ha sido confirmada con éxito</h1>
        <h3>Los datos de su reserva son los siguientes:</h3>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Pista</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Material</th>
                <th>Normas</th>
            </tr>
            <tr>
                <?php foreach ($reserva as $dato): ?>
                    <td><?= e($dato) ?></td>
                <?php endforeach; ?>
            </tr>
        </table>
    <?php endif; ?>
</body>

</html>