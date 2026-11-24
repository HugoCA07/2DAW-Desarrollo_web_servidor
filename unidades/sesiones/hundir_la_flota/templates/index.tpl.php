<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hundir la flota - Hugo Cruz Aranda</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h1>Hundir la flota - Hugo Cruz Aranda</h1>

    <h2><?= htmlspecialchars($_SESSION['flash_messages']['game'] ?? '') ?></h2>
    <?php unset($_SESSION['flash_messages']['game']) ?>

    <?php if (isset($_SESSION['flota']['tablero'])): ?>
        <form action="" method="POST">
            <table>
                <tbody>
                    <?php foreach ($_SESSION['flota']['tablero'] as $f => $fila): ?>
                        <tr>
                            <?php foreach ($fila as $c => $celda): ?>
                                <td>
                                    <label>
                                        <?php if ($_SESSION['flota']['tablero'][$f][$c] < 2): ?>
                                            <input type="radio" name="casilla" value="<?= htmlspecialchars($f) ?>,<?= htmlspecialchars($c) ?>" required>
                                        <?php elseif ($_SESSION['flota']['tablero'][$f][$c] === 2): ?>
                                            <?= htmlspecialchars('💥') ?>
                                        <?php elseif ($_SESSION['flota']['tablero'][$f][$c] === 3): ?>
                                            <?= htmlspecialchars('🌊') ?>
                                        <?php endif; ?>
                                    </label>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php if ($_SESSION['flota']['aciertos'] !== 3 && $_SESSION['flota']['intentos'] !== 0): ?>
                <button type="submit" name="operacion" value="shoot">Disparar</button>
            <?php endif; ?>
        </form>
        <form action="" method="POST">
            <button type="submit" name="operacion" value="restart">Reiniciar</button>
        </form>
    <?php endif; ?>
</body>

</html>