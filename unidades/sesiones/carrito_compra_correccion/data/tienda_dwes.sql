-- ============================================================
-- Actividad: Tienda con carrito en sesión · tienda_dwes.sql
-- Crea la base de datos, el usuario 'examen', todas las tablas
-- y datos de prueba. Ejecutar como root en phpMyAdmin.
-- Se puede ejecutar varias veces sin errores.
-- ============================================================

CREATE DATABASE IF NOT EXISTS tienda_dwes
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE tienda_dwes;

-- Usuario de MySQL para la aplicación
CREATE USER IF NOT EXISTS 'examen'@'localhost' IDENTIFIED BY 'examen';
GRANT SELECT, INSERT, UPDATE, DELETE ON tienda_dwes.* TO 'examen'@'localhost';
FLUSH PRIVILEGES;

-- Borramos tablas en orden inverso a sus claves ajenas
DROP TABLE IF EXISTS lineas_pedido;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(100) NOT NULL,
    nombre     VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    CONSTRAINT uq_usuarios_email UNIQUE (email)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- Tabla de productos
CREATE TABLE productos (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(8, 2) NOT NULL,
    stock  INT UNSIGNED NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- Tabla de pedidos
CREATE TABLE pedidos (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    total      DECIMAL(10, 2) NOT NULL,
    fecha      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- Tabla de líneas de cada pedido
CREATE TABLE lineas_pedido (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id       INT UNSIGNED NOT NULL,
    producto_id     INT UNSIGNED NOT NULL,
    cantidad        INT UNSIGNED NOT NULL,
    precio_unitario DECIMAL(8, 2) NOT NULL,
    CONSTRAINT fk_lineas_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_lineas_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- Usuarios de prueba
--   ana@ejemplo.com   ->  ana12345
--   luis@ejemplo.com  ->  luis12345
INSERT INTO usuarios (email, nombre, contrasena) VALUES
('ana@ejemplo.com',  'Ana García',  '$2y$10$D81jVzelxmIK5hpW7PZ3SuVVrtLYtAMeQyX6Bbot.Y8LsqU6Fqae2'),
('luis@ejemplo.com', 'Luis Romero', '$2y$10$kFTKfWc8VYVfDq5N4HfI7uc45avnmMXUChB8MFyQpaLOHkrAi0ZxC');

-- Productos de prueba (incluye uno con stock 0 para probar agotados)
INSERT INTO productos (nombre, precio, stock) VALUES
('Teclado Mecánico RGB', 59.95, 10),
('Ratón Inalámbrico Pro', 29.50, 15),
('Monitor 24" IPS 144Hz', 149.00, 4),
('Auriculares Gaming 7.1', 45.00, 8),
('Alfombrilla XL Antideslizante', 14.99, 25),
('Webcam Full HD 1080p', 39.90, 0);