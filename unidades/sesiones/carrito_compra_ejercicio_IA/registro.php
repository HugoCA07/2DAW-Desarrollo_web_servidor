<?php

include_once(__DIR__ . '/lib/init.php');
include_once(__DIR__ . '/lib/functions.php');

inicioSesion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['usuario'])) {
        $user = validarUsuario($_POST['usuario']);

        if ($user['isValid']) {
            $_SESSION['usuario'] = $user['valor'];
        } else {
            $_SESSION['flash_messages']['usuario'] = $user['mensaje'];
        }
    }

    if (isset($_POST['email'])) {
        $email = validarEmail($_POST['email']);

        if ($email['isValid']) {
            $_SESSION['email'] = $email['valor'];
        } else {
            $_SESSION['flash_messages']['email'] = $email['mensaje'];
        }
    }

    if (isset($_POST['password1']) && isset($_POST['password2'])) {
        $password = validarPassword($_POST['password1'], $_POST['password2']);

        if ($password['isValid']) {
            $passwordhasheada = hashearPassword($password['valor']);
            $res = registrarUsuario($_SESSION['email'], $_SESSION['usuario'], $passwordhasheada);
            if ($res) {
                unset($_SESSION['usuario']);
                unset($_SESSION['email']);
                $_SESSION['flash_messages']['register'] = 'Usuario registrado correctamente';
            }
        } else {
            $_SESSION['flash_messages']['password'] = $password['mensaje'];
        }
    }
}

include(__DIR__ . '/templates/registro.tpl.php');
