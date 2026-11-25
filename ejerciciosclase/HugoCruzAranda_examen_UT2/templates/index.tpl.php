<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tablero de colores</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #eef1f5;
            color: #2c3e50;
            padding: 30px 15px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        header {
            background: #2c3e50;
            color: #fff;
            padding: 25px 30px;
        }

        header h1 {
            font-size: 1.6em;
        }

        header p {
            opacity: 0.8;
            margin-top: 4px;
        }

        main {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            padding: 30px;
        }

        @media (max-width: 760px) {
            main {
                grid-template-columns: 1fr;
            }
        }

        h2 {
            font-size: 1.1em;
            margin-bottom: 15px;
            padding-bottom: 6px;
            border-bottom: 2px solid #eef1f5;
        }

        /* ---------- Mensajes ---------- */
        .mensaje {
            margin: 20px 30px 0;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
        }

        .mensaje.ok {
            background: #e8f8ef;
            color: #1e8449;
            border-left: 4px solid #27ae60;
        }

        .mensaje.error {
            background: #fdecea;
            color: #b03a2e;
            border-left: 4px solid #e74c3c;
        }

        /* ---------- Formularios ---------- */
        .campo {
            margin-bottom: 14px;
        }

        .campo label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .campo input,
        .campo select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccd3db;
            border-radius: 6px;
            font-size: 1em;
        }

        .campo.con-error input,
        .campo.con-error select {
            border-color: #e74c3c;
        }

        .campo .error {
            display: block;
            color: #e74c3c;
            font-size: 0.85em;
            margin-top: 4px;
        }

        .botones {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            padding: 9px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: 600;
            cursor: pointer;
            color: #fff;
        }

        .btn-pintar {
            background: #2980b9;
        }

        .btn-borrar {
            background: #7f8c8d;
        }

        .btn-reiniciar {
            background: #c0392b;
        }

        .btn-deshacer {
            background: #8e44ad;
        }

        button:hover {
            filter: brightness(1.1);
        }

        .acciones {
            margin-top: 25px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ---------- Tablero ---------- */
        .tablero {
            border-collapse: collapse;
            margin: 0 auto;
        }

        .tablero th {
            width: 50px;
            height: 30px;
            font-size: 0.85em;
            color: #7f8c8d;
        }

        .tablero td.casilla {
            width: 50px;
            height: 50px;
            border: 1px solid #d5dbe1;
            background: #fafbfc;
        }

        /* Un color de la paleta = una clase */
        .c-Rojo {
            background: #e74c3c !important;
        }

        .c-Azul {
            background: #3498db !important;
        }

        .c-Verde {
            background: #2ecc71 !important;
        }

        .c-Amarillo {
            background: #f1c40f !important;
        }

        .c-Morado {
            background: #9b59b6 !important;
        }

        .c-Naranja {
            background: #e67e22 !important;
        }

        /* ---------- Estadísticas ---------- */
        .estadisticas {
            margin-top: 30px;
        }

        .resumen {
            margin-bottom: 10px;
        }

        .recuento {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .recuento li {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            background: #f4f6f8;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .muestra {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        footer {
            background: #f4f6f8;
            text-align: center;
            padding: 12px;
            font-size: 0.85em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
<header>
    <h1>Tablero de colores</h1>
    <p>Desarrollo Web en Entorno Servidor · Sesiones en PHP</p>
</header>
<!-- TERMINAR ESTO -->

<p><?= htmlspecialchars($_SESSION['mensajes_flash']['borrar'] ?? '') ?></p>
<?php unset($_SESSION['mensajes_flash']['borrar']) ?>

<p><?= htmlspecialchars($_SESSION['mensajes_flash']['color'] ?? '') ?></p>
<?php unset($_SESSION['mensajes_flash']['color']) ?>

<p><?= htmlspecialchars($_SESSION['mensajes_flash']['tablero'] ?? '') ?></p>
<?php unset($_SESSION['mensajes_flash']['tablero']) ?>

<main>

    <!-- ==================== COLUMNA IZQUIERDA ==================== -->
    <section>
        <h2>Pintar o borrar casilla</h2>

        <form method="POST" action="" novalidate>

            <div class="campo">
                <label for="fila">Fila (1-<?= htmlspecialchars(TAMANO . ')') ?? ' )' ?></label>
                <input type="number" id="fila" name="fila"
                       value="<?= htmlspecialchars($_SESSION['colores']['fila'] ?? 0) ?>">
                <span><?= htmlspecialchars($_SESSION['mensajes_flash']['fila'] ?? '') ?></span>
                <?php unset($_SESSION['mensajes_flash']['fila']) ?>
            </div>

            <div class="campo">
                <label for="columna">Columna (1-<?= htmlspecialchars(TAMANO . ')') ?? ' )' ?></label>
                <input type="number" id="columna" name="columna"
                       value="<?= htmlspecialchars($_SESSION['colores']['fila'] ?? 0) ?>">
                <span><?= htmlspecialchars($_SESSION['mensajes_flash']['columna'] ?? '') ?></span>
                <?php unset($_SESSION['mensajes_flash']['columna']) ?>
            </div>

            <div class="campo">
                <label for="color">Color</label>
                <select id="color" name="color">
                    <option value="null">-- Elige un color --</option>
                    <option value="Rojo">Rojo</option>
                    <option value="Azul">Azul</option>
                    <option value="Verde" selected>Verde</option>
                    <option value="Amarillo">Amarillo</option>
                    <option value="Morado">Morado</option>
                    <option value="Naranja">Naranja</option>
                </select>

            </div>

            <div class="botones">
                <button type="submit" name="accion" value="pintar" class="btn-pintar">Pintar</button>
                <button type="submit" name="accion" value="borrar" class="btn-borrar">Borrar casilla</button>
            </div>
        </form>

        <div class="acciones">
            <form method="POST" action="">
                <button type="submit" name="accion" value="reiniciar" class="btn-reiniciar">Reiniciar tablero</button>
            </form>

            <!-- AMPLIACIÓN -->
            <form method="POST" action="">
                <button type="submit" name="accion" value="deshacer" class="btn-deshacer">Deshacer</button>
            </form>
        </div>
    </section>

    <!-- ==================== COLUMNA DERECHA ==================== -->
    <section>
        <h2>Tablero</h2>

        <?php if (isset($_SESSION['colores']['tablero'])): ?>
            <table class="tablero">
                <tr>
                    <th></th>
                    <?php for ($i = 1; $i <= TAMANO; $i++) {
                        echo '<th>' . $i . '</th>';
                    } ?>
                </tr>
                <?php for ($i = 1; $i <= TAMANO; $i++) {
                    echo '<tr>';
                    echo '<th>' . $i . '</th>';
                    for ($j = 1; $j <= TAMANO; $j++) {
                        if ($_SESSION['colores']['tablero'][$i][$j] === null) {
                            echo '<td class="casilla"></td>';
                        } else {
                            echo '<td class="casilla c-' . $_SESSION['colores']['tablero'][$i][$j] . '"></td>';
                        }
                    }
                    echo '</tr>';
                } ?>
            </table>
        <?php endif; ?>

        <div class="estadisticas">
            <h2>Estadísticas</h2>
            <p class="resumen">Casillas pintadas:
                <strong><?= htmlspecialchars($_SESSION['colores']['casillas_pintadas'] ?? '') ?>
                    de <?= htmlspecialchars($_SESSION['colores']['casillas_totales'] ?? '') ?></strong>
                (<?= htmlspecialchars(number_format($_SESSION['colores']['casillas_pintadas'] / $_SESSION['colores']['casillas_totales'] * 100, 2, '.', '') ?? 0) ?>
                %)
            </p>
            <ul class="recuento">
                <?php foreach ($_SESSION['colores']['contadores'] as $color => $valor): ?>
                    <li>
                        <span class="muestra c-<?= htmlspecialchars($color) ?>"></span>
                        <?= htmlspecialchars($color . ': ' . $valor) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

</main>

<footer>Examen DWES · Curso 2026/2027</footer>
</body>
</html>