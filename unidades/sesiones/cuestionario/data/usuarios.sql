-- ============================================================
-- Actividad: login con sesiones · usuarios.sql
-- Crea la base de datos, el usuario de la aplicación, la tabla
-- y dos usuarios de prueba. Ejecutar como root en phpMyAdmin
-- (pestaña SQL). Se puede ejecutar varias veces sin errores.
-- ============================================================

-- Base de datos
CREATE DATABASE IF NOT EXISTS login_dwes
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE login_dwes;

-- Usuario de MySQL para la aplicación (la aplicación no usa root).
-- Solo tiene permisos sobre login_dwes.
CREATE USER IF NOT EXISTS 'login_dwes'@'localhost' IDENTIFIED BY 'Login2026.';
GRANT SELECT, INSERT, UPDATE, DELETE ON login_dwes.* TO 'login_dwes'@'localhost';

-- Tabla de usuarios (se borra y se crea de nuevo para empezar limpio)
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(100) NOT NULL,
    nombre     VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,          -- hash de password_hash(), nunca la contraseña
    CONSTRAINT uq_usuarios_email UNIQUE (email)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- Usuarios de prueba
--   ana@ejemplo.com   ->  ana12345
--   luis@ejemplo.com  ->  luis12345
INSERT INTO usuarios (email, nombre, contrasena) VALUES
('ana@ejemplo.com',  'Ana García',  '$2y$10$D81jVzelxmIK5hpW7PZ3SuVVrtLYtAMeQyX6Bbot.Y8LsqU6Fqae2'),
('luis@ejemplo.com', 'Luis Romero', '$2y$10$kFTKfWc8VYVfDq5N4HfI7uc45avnmMXUChB8MFyQpaLOHkrAi0ZxC');
