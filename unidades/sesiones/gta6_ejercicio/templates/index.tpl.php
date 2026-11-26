<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTA VI · Hugo Cruz Aranda</title>
    <style>
        :root {
            --bg-dark: #0d0b14;
            --panel-bg: rgba(24, 20, 38, 0.85);
            --neon-pink: #ff2a85;
            --neon-cyan: #00f0ff;
            --neon-purple: #8a2be2;
            --text-main: #f4f4f8;
            --text-muted: #a09cb0;
            --border-subtle: rgba(255, 255, 255, 0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at top center, #241336 0%, var(--bg-dark) 75%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
            gap: 1.5rem;
        }

        /* Título principal estilo neón */
        h1 {
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: 4px;
            text-transform: uppercase;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-pink));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 12px rgba(255, 42, 133, 0.45));
        }

        /* Mensajes flash de éxito o aviso */
        h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--neon-cyan);
            text-shadow: 0 0 8px rgba(0, 240, 255, 0.4);
            min-height: 1.5rem;
        }

        h2:empty {
            display: none;
        }

        /* Tabla del Mapa */
        .mapa {
            border-collapse: separate;
            border-spacing: 3px;
            background: #141122;
            padding: 10px;
            border-radius: 12px;
            border: 1px solid rgba(0, 240, 255, 0.25);
            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.7),
                0 0 20px rgba(255, 42, 133, 0.15);
        }

        .mapa th,
        .mapa td {
            width: 52px;
            height: 52px;
            border-radius: 6px;
            text-align: center;
            transition: transform 0.15s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .mapa th {
            background: rgba(255, 255, 255, 0.05);
            color: var(--neon-cyan);
            height: 32px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid var(--border-subtle);
        }

        .mapa td {
            background: #1e1b2e;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .mapa td:hover {
            transform: scale(1.06);
            z-index: 2;
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Tipos de casilla */
        .carretera {
            background: linear-gradient(145deg, #2b2b36, #1f1f27) !important;
            border: 1px dashed rgba(255, 255, 255, 0.18) !important;
        }

        .acera {
            background: linear-gradient(145deg, #b8b8c7, #8e8e9e) !important;
            box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.4);
        }

        .coche {
            background: linear-gradient(135deg, #ff3b3b, #b71c1c) !important;
            box-shadow: 0 0 12px rgba(229, 57, 53, 0.75);
            border: 1px solid #ff8a80 !important;
        }

        .policia {
            background: linear-gradient(135deg, #00b0ff, #1565c0) !important;
            box-shadow: 0 0 14px rgba(0, 176, 255, 0.85);
            border: 1px solid #80d8ff !important;
            animation: sirena 1.4s infinite alternate;
        }

        @keyframes sirena {
            0% {
                box-shadow: 0 0 12px rgba(0, 176, 255, 0.9);
            }

            100% {
                box-shadow: 0 0 14px rgba(255, 42, 133, 0.9);
            }
        }

        /* Panel de control (Formulario) */
        form {
            background: var(--panel-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 1.5rem 2rem;
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
        }

        form label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-top: 0.4rem;
        }

        form input[type="number"],
        form select {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background: #120f1d;
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        form input[type="number"]:focus,
        form select:focus {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 0 3px rgba(0, 240, 255, 0.15);
        }

        /* Mensajes de error debajo de cada campo */
        form span {
            font-size: 0.8rem;
            color: #ff5277;
            font-weight: 500;
            min-height: 1rem;
        }

        form span:empty {
            display: none;
        }

        /* Botones */
        form button {
            margin-top: 0.6rem;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease, opacity 0.2s;
        }

        form button[value="pintar"] {
            background: linear-gradient(90deg, var(--neon-pink), var(--neon-purple));
            color: #fff;
            box-shadow: 0 4px 15px rgba(255, 42, 133, 0.4);
        }

        form button[value="pintar"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 42, 133, 0.65);
        }

        form button[value="borrar"] {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-subtle);
        }

        form button[value="borrar"]:hover {
            color: #fff;
            border-color: #ff5277;
            background: rgba(255, 82, 119, 0.1);
        }

        form button:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>
    <h1>GTA VI</h1>

    <?php if (isset($_SESSION['mapa'])): ?>
        <h2><?= htmlspecialchars($_SESSION['mensajes_flash']['exito']['pintar'] ?? '') ?></h2>
        <?php unset($_SESSION['mensajes_flash']['exito']['pintar']) ?>

        <table class="mapa">
            <thead>
                <tr>
                    <?php for ($i = 1; $i <= COLUMNAS; $i++): ?>
                        <th><?= htmlspecialchars($i) ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($_SESSION['mapa'] as $fila): ?>

                    <tr>

                        <?php foreach ($fila as $celda): ?>

                            <td class="<?= htmlspecialchars($celda) ?? '' ?>" </td>

                            <?php endforeach; ?>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <form action="index.php" method="POST" novalidate>
            <label for="fila">FILA:</label>
            <input type="number" name="fila" value="<?= htmlspecialchars($_SESSION['lastrow'] ?? '1')  ?>">
            <span>
                <?= htmlspecialchars($_SESSION['mensajes_flash']['error']['fila'] ?? '') ?>
            </span>
            <?php unset($_SESSION['mensajes_flash']['error']['fila']) ?>

            <label for="columna">COLUMNA:</label>
            <input type="number" name="columna" value="<?= htmlspecialchars($_SESSION['lastcol'] ?? '1')  ?>">
            <span>
                <?= htmlspecialchars($_SESSION['mensajes_flash']['error']['columna'] ?? '') ?>
            </span>
            <?php unset($_SESSION['mensajes_flash']['error']['columna']) ?>

            <select name="casilla">
                <option value="">--- ELIGE UNA CASILLA ---</option>
                <?php foreach (TIPOS as $key => $value) : ?>
                    <?php if ($_SESSION['lastceil'] === $key): ?>
                        <option value="<?= htmlspecialchars($key) ?>" selected><?= htmlspecialchars($value) ?></option>
                    <?php else: ?>
                        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($value) ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <span>
                <?= htmlspecialchars($_SESSION['mensajes_flash']['error']['celda'] ?? '') ?>
            </span>
            <?php unset($_SESSION['mensajes_flash']['error']['celda']) ?>

            <button type="submit" name="accion" value="pintar">Pintar</button>
            <button type="submit" name="accion" value="borrar">Borrar</button>
            <button type="submit" name="accion" value="reiniciar">Reiniciar Tablero</button>
        </form>
    <?php else: ?>
        <h2>NO EXISTE EL MAPA</h2>
    <?php endif; ?>
</body>

</html>