<?php

include_once (__DIR__ . '/bd.php');

session_start();

function validarUsuario($usuario): array
{
    $resultado = true;
    $mensaje = '';

    if (empty(trim($usuario))) {
        $resultado = false;
        $mensaje = 'El usuario está vacío';
    } else if (FILTER_UNSAFE_RAW === $usuario) {
        $mensaje = 'El usuario no es correcto';
        $resultado = false;
    } else if (strlen($usuario) < 1) {
        $mensaje = 'El usuario no puede contener menos de 1 caracteres';
        $resultado = false;
    } else if (strlen($usuario) > 100) {
        $mensaje = 'El usuario no puede contener más de 100 caracteres';
        $resultado = false;
    } else if (str_contains($usuario, '@')) {
        $mensaje = 'El usuario no puede contener un arroba';
        $resultado = false;
    } else if (str_contains($usuario, '.')) {
        $mensaje = 'El usuario no puede contener un punto';
        $resultado = false;
    } else {
        $usuario = trim($usuario);
    }

    return ['resultado' => $resultado,
            'mensaje' => $mensaje,
            'usuario' => $usuario
    ];
}

function validarEmail($email): array
{
    $resultado = true;
    $mensaje = '';

    if (empty(trim($email))) {
        $resultado = false;
        $mensaje = 'El email está vacío';
    } else if (FILTER_VALIDATE_EMAIL === $email) {
        $mensaje = 'El email no es correcto';
        $resultado = false;
    } else if (strlen($email) < 1) {
        $mensaje = 'El email no puede contener menos de 1 caracteres';
        $resultado = false;
    } else if (strlen($email) > 100) {
        $mensaje = 'El email no puede contener más de 100 caracteres';
        $resultado = false;
    } else {
        $email = trim($email);
    }

    return ['resultado' => $resultado,
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
    } else if (FILTER_UNSAFE_RAW === $password) {
        $mensaje = 'La contraseña no es correcta';
        $resultado = false;
    } else if (strlen($password) < 8) {
        $mensaje = 'La contraseña no debe tener menos de 8 caracteres';
        $resultado = false;
    } else if (strlen($password) > 255) {
        $mensaje = 'La contraseña no debe tener más de 255 caracteres';
        $resultado = false;
    } else {
        $password = trim($password);
    }

    return ['resultado' => $resultado,
            'mensaje' => $mensaje,
            'password' => $password
    ];
}

function hashearPassword($password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function existeEmail ($email): bool|null
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT email FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException) {
            return null;
        }
    } else {
        return null;
    }
}

function registrarUsuario($usuario, $password, $email)
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (email, nombre, contrasena) VALUES (:email, :nombre, :contrasena)");
            $stmt->execute(['email' => $email, 'nombre' => $usuario, 'contrasena' => $password]);
        } catch (PDOException) {
            return null;
        }
    } else {
        return null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = filter_input(INPUT_POST, 'usuario');
    $password = filter_input(INPUT_POST, 'password');
    $email = filter_input(INPUT_POST, 'email');
    if (!empty($usuario) && !empty($password) && !empty($email)) {
        $usuario = validarUsuario($usuario);
        if ($usuario['resultado']) {
            $_SESSION['usuario'] = $usuario['usuario'];
            $email = validarEmail($email);

            if ($email['resultado']) {
                $_SESSION['email'] = $email['email'];
                $password = validarPassword($password);

                if ($password['resultado']) {
                    $passwordHasheada = hashearPassword($password['password']);
                    $res = existeEmail($email['email']);
                    if ($res === false) {
                        registrarUsuario($usuario['usuario'], $passwordHasheada, $email['email']);
                        $_SESSION = [];
                        header("location: login.php", true, 303);
                        exit();
                    } else {
                        $_SESSION['flash_messages']['emailmsg'] = 'Ese email ya está registrado';
                    }
                } else {
                    $_SESSION['flash_messages']['passwordmsg'] = $password['mensaje'];
                }
            } else {
                $_SESSION['flash_messages']['emailmsg'] = $email['mensaje'];
            }
        } else {
            $_SESSION['flash_messages']['usuariomsg'] = $usuario['mensaje'];
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
<form method="POST" action="">
    <label for="usuario">Usuario:</label>
    <input type="text" name="usuario" id="usuario" placeholder="Usuario" required
           value="<?php echo htmlspecialchars($_SESSION['usuario'] ?? ''); ?>">
    <span>
        <?php if (isset($_SESSION['flash_messages']['usuariomsg'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_messages']['usuariomsg']) ?? '' ?>
            <?php unset($_SESSION['flash_messages']['usuariomsg']); ?>
        <?php endif; ?>
    </span>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" placeholder="Email@email.com" required
           value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
    <span>
        <?php if (isset($_SESSION['flash_messages']['emailmsg'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_messages']['emailmsg']) ?? '' ?>
            <?php unset($_SESSION['flash_messages']['emailmsg']); ?>
        <?php endif; ?>
    </span>
    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" placeholder="Contraseña" required>
    <span>
        <?php if (isset($_SESSION['flash_messages']['passwordmsg'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_messages']['passwordmsg']) ?? '' ?>
            <?php unset($_SESSION['flash_messages']['passwordmsg']); ?>
        <?php endif; ?>
    </span>
    <button type="submit">Register</button>
</form>
<a href="login.php">Login</a>
</body>
</html>