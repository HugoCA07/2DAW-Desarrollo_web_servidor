<?php

include_once(__DIR__ . '/bd.php');

session_start();

if (isset($_SESSION['id'])) {
    header('Location: bienvenida.php');
    exit;
}

function validarUsuario($usuario): array
{
    $usuario = trim($usuario);
    $resultado = true;
    $mensaje = '';

    if (empty($usuario)) {
        $resultado = false;
        $mensaje = 'El usuario está vacío';
    } else if (strlen($usuario) > 100) {
        $mensaje = 'El usuario no puede contener más de 100 caracteres';
        $resultado = false;
    } else if (str_contains($usuario, '@')) {
        $mensaje = 'El usuario no puede contener un arroba';
        $resultado = false;
    } else if (str_contains($usuario, '.')) {
        $mensaje = 'El usuario no puede contener un punto';
        $resultado = false;
    }

    return [
        'resultado' => $resultado,
        'mensaje' => $mensaje,
        'usuario' => $usuario
    ];
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

function validarPassword($password, $password2): array
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
    } else if ($password !== $password2) {
        $mensaje = 'Las contraseñas no coinciden';
        $resultado = false;
    }

    return [
        'resultado' => $resultado,
        'mensaje' => $mensaje,
        'password' => $password
    ];
}

function hashearPassword($password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function existeEmail($email): bool|null
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT email FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->rowCount() > 0;
        } catch (PDOException) {
            return null;
        }
    }
    return null;
}

function registrarUsuario($usuario, $password, $email): bool
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (email, nombre, contrasena) VALUES (:email, :nombre, :contrasena)");
            return $stmt->execute(['email' => $email, 'nombre' => $usuario, 'contrasena' => $password]);
        } catch (PDOException) {
            return false;
        }
    }
    return false;
}

$nombreVal = '';
$emailVal = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioInput = $_POST['usuario'] ?? '';
    $emailInput = $_POST['email'] ?? '';
    $passwordInput = $_POST['password'] ?? '';
    $password2Input = $_POST['password2'] ?? '';

    $usuario = validarUsuario($usuarioInput);
    $email = validarEmail($emailInput);
    $password = validarPassword($passwordInput, $password2Input);

    $nombreVal = $usuario['usuario'];
    $emailVal = $email['email'];

    if (!$usuario['resultado']) {
        $_SESSION['flash_messages']['usuariomsg'] = $usuario['mensaje'];
    }
    if (!$email['resultado']) {
        $_SESSION['flash_messages']['emailmsg'] = $email['mensaje'];
    }
    if (!$password['resultado']) {
        $_SESSION['flash_messages']['passwordmsg'] = $password['mensaje'];
    }

    if ($usuario['resultado'] && $email['resultado'] && $password['resultado']) {
        $res = existeEmail($email['email']);
        if ($res === false) {
            $passwordHasheada = hashearPassword($password['password']);
            if (registrarUsuario($usuario['usuario'], $passwordHasheada, $email['email'])) {
                $_SESSION['flash_messages']['loginmsg'] = 'Registro completado. Ya puedes iniciar sesión';
                header("Location: login.php", true, 303);
                exit;
            }
        } else {
            $_SESSION['flash_messages']['emailmsg'] = 'Ese email ya está registrado';
        }
    }
}

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <form method="POST" action="">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" placeholder="Usuario" required
            value="<?= htmlspecialchars($nombreVal) ?>">
        <span>
            <?php if (isset($_SESSION['flash_messages']['usuariomsg'])): ?>
                <?= htmlspecialchars($_SESSION['flash_messages']['usuariomsg']) ?>
                <?php unset($_SESSION['flash_messages']['usuariomsg']); ?>
            <?php endif; ?>
        </span>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Email@email.com" required
            value="<?= htmlspecialchars($emailVal) ?>">
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

        <label for="password2">Repetir contraseña:</label>
        <input type="password" name="password2" id="password2" placeholder="Repite la contraseña" required minlength="8">

        <button type="submit">Register</button>
    </form>
    <a href="login.php">Login</a>
</body>

</html>