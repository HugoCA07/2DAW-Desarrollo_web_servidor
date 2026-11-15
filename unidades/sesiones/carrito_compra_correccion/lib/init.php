<?php

function inicioSesion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
