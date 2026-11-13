<?php

include_once(__DIR__ . '/bd.php');

session_start();

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

function validarLogin($email, $password): int|null
{
    $pdo = conexion();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario['contrasena'] && password_verify($password, $usuario['contrasena'])) {
                return $usuario['id'];
            }

            return null;
        } catch (PDOException) {
            return null;
        }
    } else {
        return null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = validarEmail($_POST['email']);
        $password = validarPassword($_POST['password']);
        if ($email['resultado'] && $password['resultado']) {
            $res = validarLogin($email['email'], ($password['password']));
            if ($res) {
                $_SESSION['id'] = $res;
                header('Location: bienvenida.php',true, 302);
            } else {
                $_SESSION['flash_messages']['loginmsg'] = 'El usuario/contraseña no son correctos';
            }
        } else {
            $_SESSION['flash_messages']['emailmsg'] = $email['mensaje'];
            $_SESSION['flash_messages']['passwordmsg'] = $password['mensaje'];
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
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>

<h2>
    <?= $_SESSION['flash_messages']['loginmsg'] ?? '' ?>
</h2>

<form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" placeholder="Ejemplo@ejemplo.com" required>
    <span>
        <?php if (isset($_SESSION['flash_messages']['emailmsg'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_messages']['emailmsg']) ?? '' ?>
            <?php unset($_SESSION['flash_messages']['emailmsg']); ?>
        <?php endif; ?>
    </span>
    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" placeholder="Contraseña" required minlength="8">
    <span>
        <?php if (isset($_SESSION['flash_messages']['passwordmsg'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_messages']['passwordmsg']) ?? '' ?>
            <?php unset($_SESSION['flash_messages']['passwordmsg']); ?>
        <?php endif; ?>
    </span>
    <button type="submit">Login</button>
</form>
<a href="registro.php">Register</a>
</body>
</html>
