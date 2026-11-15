<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

inicioSesion();

if (isset($_SESSION['id'])) {
    header('Location: bienvenida.php', true, 302);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = validarUsuario($_POST['usuario'] ?? '');
    $email = validarEmail($_POST['email'] ?? '');
    $password = validarPassword($_POST['password1'] ?? '', $_POST['password2'] ?? '');

    $_SESSION['usuario_reg'] = $user['valor'];
    $_SESSION['email_reg'] = $email['valor'];

    if (!$user['isValid']) {
        $_SESSION['flash_messages']['usuario'] = $user['mensaje'];
    }
    if (!$email['isValid']) {
        $_SESSION['flash_messages']['email'] = $email['mensaje'];
    }
    if (!$password['isValid']) {
        $_SESSION['flash_messages']['password'] = $password['mensaje'];
    }

    if ($user['isValid'] && $email['isValid'] && $password['isValid']) {
        $passwordHasheada = hashearPassword($password['valor']);
        $res = registrarUsuario($email['valor'], $user['valor'], $passwordHasheada);

        if ($res === true) {
            unset($_SESSION['usuario_reg'], $_SESSION['email_reg']);
            $_SESSION['flash_messages']['login'] = 'Usuario registrado correctamente. Inicia sesión';
            header('Location: login.php', true, 303);
            exit;
        } else {
            $_SESSION['flash_messages']['register'] = 'No se pudo registrar (puede que el email ya exista)';
        }
    }

    header('Location: registro.php', true, 303);
    exit;
}

include(__DIR__ . '/templates/registro.tpl.php');
