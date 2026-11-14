<?php

include_once(__DIR__ . '/bd.php');

session_start();

if (isset($_SESSION['id'])) {
    header('Location: bienvenida.php');
    exit;
}

function validarEmail($email): array
{
    $email = trim($email);
    $resultado = true;
    $mensaje = '';

    if (empty($email)) {
        $resultado = false;
        $mensaje = 'El email está vacío';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El email no es correcto';
        $resultado = false;
    } else if (strlen($email) > 100) {
        $mensaje = 'El email no puede contener más de 100 caracteres';
        $resultado = false;
    }

    return [
        'resultado' => $resultado,
        'mensaje' => $mensaje,
        'email' => $email
    ];
}

function validarPassword($password): array
{
    $resultado = true;
    $mensaje = '';

    if (empty($password)) {
        $resultado = false;
        $mensaje = 'La contraseña está vacía';
    } else if (strlen($password) < 8) {
        $mensaje = 'La contraseña no debe tener menos de 8 caracteres';
        $resultado = false;
    } else if (strlen($password) > 255) {
        $mensaje = 'La contraseña no debe tener más de 255 caracteres';
        $resultado = false;
    }

    return [
        'resultado' => $resultado,
        'mensaje' => $mensaje,
        'password' => $password
    ];
}

function validarLogin($email, $password): array|false|null
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['contrasena'])) {
                return $usuario;
            }

            return false;
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = validarEmail($_POST['email']);
        $password = validarPassword($_POST['password']);

        if ($email['resultado'] && $password['resultado']) {
            $usuario = validarLogin($email['email'], $password['password']);
            if ($usuario) {
                session_regenerate_id(true);
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                header('Location: bienvenida.php', true, 302);
                exit;
            } else {
                $_SESSION['flash_messages']['loginmsg'] = 'Email o contraseña incorrectos';
            }
        } else {
            $_SESSION['flash_messages']['emailmsg'] = $email['mensaje'];
            $_SESSION['flash_messages']['passwordmsg'] = $password['mensaje'];
        }
    }
}

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>

    <?php if (isset($_SESSION['flash_messages']['loginmsg'])): ?>
        <h2><?= htmlspecialchars($_SESSION['flash_messages']['loginmsg']) ?></h2>
        <?php unset($_SESSION['flash_messages']['loginmsg']); ?>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Ejemplo@ejemplo.com" required>
        <span>
            <?php if (isset($_SESSION['flash_messages']['emailmsg'])): ?>
                <?= htmlspecialchars($_SESSION['flash_messages']['emailmsg']) ?>
                <?php unset($_SESSION['flash_messages']['emailmsg']); ?>
            <?php endif; ?>
        </span>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" placeholder="Contraseña" required minlength="8">
        <span>
            <?php if (isset($_SESSION['flash_messages']['passwordmsg'])): ?>
                <?= htmlspecialchars($_SESSION['flash_messages']['passwordmsg']) ?>
                <?php unset($_SESSION['flash_messages']['passwordmsg']); ?>
            <?php endif; ?>
        </span>

        <button type="submit">Login</button>
    </form>
    <a href="registro.php">Register</a>
</body>

</html>