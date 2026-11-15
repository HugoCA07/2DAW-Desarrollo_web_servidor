<?php

include_once(__DIR__ . '/lib/functions.php');
include_once(__DIR__ . '/lib/init.php');

inicioSesion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = validarEmail($_POST['email']);
        if ($email['isValid']) {
            $_SESSION['email'] = $email;
        }
    }

    if (isset($_POST['password'])) {
        $password = validarPassword($_POST['password'], $_POST['password']);
    }

    if (!empty($_SESSION['email']['isValid']) && !empty($password['isValid']) && isset($password['valor'])) {
        $usuario = validarInicioSesion($_SESSION['email']['valor'], $password['valor']);

        if (is_array($usuario)) {
            $_SESSION = [];
            session_regenerate_id(true);
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            header('Location: bienvenida.php', true, 302);
            exit;
        } else if ($usuario === false) {
            $_SESSION['flash_messages']['login'] = 'Login incorrecto';
        } else {
            $_SESSION['flash_messages']['login'] = 'Error en la BBDD';
        }
    }
}

include(__DIR__ . '/templates/login.tpl.php');
