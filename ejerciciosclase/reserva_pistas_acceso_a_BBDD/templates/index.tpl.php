<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUSCAR PISTAS</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <!-- FORMULARIO PARA EL GET -->
    <form action="" method="get">
        <label for="pista">Pista:</label>
        <select id="pista" name="pista">
            <option value="" <?= empty($inputs[0]) ? 'selected' : '' ?>>Selecciona una pista...</option>
            <option value="Pádel 1" <?= ($inputs[0] ?? '') === 'Pádel 1' ? 'selected' : '' ?>>Pádel 1</option>
            <option value="Pádel 2" <?= ($inputs[0] ?? '') === 'Pádel 2' ? 'selected' : '' ?>>Pádel 2</option>
            <option value="Tenis" <?= ($inputs[0] ?? '') === 'Tenis' ? 'selected' : '' ?>>Tenis</option>
            <option value="Baloncesto" <?= ($inputs[0] ?? '') === 'Baloncesto' ? 'selected' : '' ?>>Baloncesto</option>
        </select>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?= isset($inputs[1]) ? htmlspecialchars($inputs[1]) : '' ?>">

        <button type="submit">Buscar</button>
    </form>

    <!-- Toda la lógica de la tabla se genera aquí directamente en la vista -->
    <hr>
    <?php if (empty($tabla_reservas)): ?>
        <h2>No hay reservas</h2>
    <?php else: ?>
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
            <?php foreach ($tabla_reservas as $reserva): ?>
                <tr>
                    <?php foreach ($reserva as $valor): ?>
                        <td><?= e($valor) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>

</html>