<?php

include_once(__DIR__ . '/lib/init.php');

inicioSesion();

$_SESSION = [];

setcookie(session_name(), '', time() - 3600, '/');

session_destroy();

header('Location: login.php', true, 302);
exit;
