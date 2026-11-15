<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h1>¡Regístrate!</h1>

    <?php if (!empty($_SESSION['flash_messages']['register'])): ?>
        <p><strong><?= htmlspecialchars($_SESSION['flash_messages']['register']) ?></strong></p>
        <?php unset($_SESSION['flash_messages']['register']); ?>
    <?php endif; ?>

    <form action="" method="POST">
        <fieldset>
            <legend>Regístrate</legend>

            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" placeholder="Usuario" required maxlength="100"
                value="<?= htmlspecialchars($_SESSION['usuario_reg'] ?? '') ?>">
            <?php unset($_SESSION['usuario_reg']); ?>
            <span>
                <?= htmlspecialchars($_SESSION['flash_messages']['usuario'] ?? '') ?>
                <?php unset($_SESSION['flash_messages']['usuario']); ?>
            </span>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Ejemplo@email.com" required maxlength="100"
                value="<?= htmlspecialchars($_SESSION['email_reg'] ?? '') ?>">
            <?php unset($_SESSION['email_reg']); ?>
            <span>
                <?= htmlspecialchars($_SESSION['flash_messages']['email'] ?? '') ?>
                <?php unset($_SESSION['flash_messages']['email']); ?>
            </span>

            <label for="password1">Contraseña:</label>
            <input type="password" name="password1" id="password1" required minlength="8">

            <label for="password2">Repite tu contraseña:</label>
            <input type="password" name="password2" id="password2" required minlength="8">
            <span>
                <?= htmlspecialchars($_SESSION['flash_messages']['password'] ?? '') ?>
                <?php unset($_SESSION['flash_messages']['password']); ?>
            </span>

            <button type="submit">Register</button>
        </fieldset>
    </form>

    <a href="login.php">Login</a>
</body>

</html>