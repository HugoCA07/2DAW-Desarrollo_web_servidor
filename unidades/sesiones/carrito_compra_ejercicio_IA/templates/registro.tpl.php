<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h1>¡Registrate!</h1>

    <?= htmlspecialchars($_SESSION['flash_messages']['register'] ?? '') ?>
    <?php unset($_SESSION['flash_messages']['register']) ?>

    <form action="" method="POST">
        <fieldset>
            <legend>Registrate</legend>

            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" placeholder="Usuario" required minlength="0" maxlength="100"
                value="<?= htmlspecialchars($_SESSION['usuario'] ?? '') ?>">
            <span>
                <?= htmlspecialchars($_SESSION['flash_messages']['usuario'] ?? '') ?>
                <?php unset($_SESSION['flash_messages']['usuario']) ?>
            </span>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Ejemplo@email.com" required minlength="0" maxlength="100"
                value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
            <span>
                <?= htmlspecialchars($_SESSION['flash_messages']['email'] ?? '') ?>
                <?php unset($_SESSION['flash_messages']['email']) ?>
            </span>

            <label for="password1">Contraseña:</label>
            <input type="password" name="password1" id="password1" required minlength="8">

            <label for="password2">Repite tu contraseña:</label>
            <input type="password" name="password2" id="password2" required minlength="8">
            <span>
                <?= htmlspecialchars($_SESSION['flash_messages']['password'] ?? '') ?>
                <?php unset($_SESSION['flash_messages']['password']) ?>
            </span>

            <button type="submit">Register</button>
        </fieldset>
    </form>

    <a href="login.php">Login</a>
</body>

</html>