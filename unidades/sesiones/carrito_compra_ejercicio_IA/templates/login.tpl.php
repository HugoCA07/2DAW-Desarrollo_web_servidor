<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <h1>¿De vuelta?, inicia sesión</h1>

    <?= htmlspecialchars($_SESSION['flash_messages']['login'] ?? '') ?>
    <?php unset($_SESSION['flash_messages']['login']) ?>

    <form action="" method="POST">
        <fieldset>
            <legend>Inicia sesión</legend>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Ejemplo@email.com" required minlength="0" maxlength="100"
                value="<?= htmlspecialchars($_SESSION['email']['valor'] ?? '') ?>">
            <span>
                <?= htmlspecialchars($_SESSION['flash_messages']['email'] ?? '') ?>
                <?php unset($_SESSION['flash_messages']['email']) ?>
            </span>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required minlength="8">

            <button type="submit">Login</button>
        </fieldset>
    </form>

    <a href="registro.php">Registrate</a>
</body>

</html>