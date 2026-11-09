<?php

session_start();

function validarUsuario($usuario): array
{
    $resultado = true;
    $mensaje = '';

    if (empty(trim($usuario))) {
        $resultado = false;
        $mensaje = 'El usuario está vacío';
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_UNSAFE_RAW);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!empty($usuario) || !empty($password) || !empty($email)) {

        if ($usuario = validarUsuario($usuario)['resultado']) {
            $_SESSION['usuario'] = $usuario;
        } else if ($email = validarEmail($email)['resultado']) {
            $_SESSION['email'] = $email;
        } else if ($password = validarPassword($password)['resultado']) {
            $password = hashearPassword($password);
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
    <label for="usuario">Usuario:</label><input type="text" name="usuario" id="usuario" placeholder="Usuario" required
                                                value="<?php echo htmlspecialchars($_SESSION['usuario'] ?? ''); ?>">
    <label for="password">Contraseña:</label><input type="password" name="password" id="password"
                                                    placeholder="Contraseña" required>
    <label for="email">Email:</label><input type="email" name="email" id="email" placeholder="Email@email.com" required
                                            value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
    <button type="submit">Register</button>
</form>
<a href="login.php">Login</a>
</body>
</html>