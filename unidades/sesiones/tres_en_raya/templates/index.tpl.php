<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tres en raya</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
    <style>
        td {
            text-align: center;
        }
    </style>
</head>
<body>
<h1>Tres en raya - Hugo Cruz Aranda</h1>

<h2>Turno de: <?= htmlspecialchars($_SESSION['partida']['turno'] ?? '') ?></h2>

<form action="" method="post">
    <table>
        <?php for ($f = 1; $f <= 3; $f++): ?>
            <tr>
                <?php for ($c = 1; $c <= 3; $c++): ?>
                    <td>
                        <?php if (!empty($_SESSION['partida']['tablero']["fila$f"]["celda$c"])): ?>
                            <?= htmlspecialchars($_SESSION['partida']['tablero']["fila$f"]["celda$c"]) ?>
                        <?php else: ?>
                            <label for="celda">
                                <input type="radio" name="celda">
                                <input type="hidden" name="fila"
                                       value="<?= htmlspecialchars($_SESSION['partida']['tablero']["fila$f"]) ?>">
                                <input type="hidden" name="columna"
                                       value="<?= htmlspecialchars($_SESSION['partida']['tablero']["fila$f"]["celda$c"]) ?>">
                            </label>
                        <?php endif; ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
    <button type="submit"></button>
</form>

</body>
</html>