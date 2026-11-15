<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

inicioSesion();

if (isset($_SESSION['id'])) {
    header('Location: bienvenida.php', true, 302);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = validarEmail($_POST['email'] ?? '');
    $password = validarPassword($_POST['password'] ?? '', $_POST['password'] ?? '');

    if ($email['isValid']) {
        $_SESSION['email_login'] = $email['valor'];
    } else {
        $_SESSION['flash_messages']['email'] = $email['mensaje'];
    }

    if (!$password['isValid']) {
        $_SESSION['flash_messages']['login'] = $password['mensaje'];
    }

    if ($email['isValid'] && $password['isValid']) {
        $usuario = validarInicioSesion($email['valor'], $password['valor']);

        if (is_array($usuario)) {
            $_SESSION = [];
            session_regenerate_id(true);
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            header('Location: bienvenida.php', true, 303);
            exit;
        } elseif ($usuario === false) {
            $_SESSION['flash_messages']['login'] = 'Email o contraseña incorrectos';
        } else {
            $_SESSION['flash_messages']['login'] = 'Error en la base de datos';
        }
    }

    header('Location: login.php', true, 303);
    exit;
}

include(__DIR__ . '/templates/login.tpl.php');
