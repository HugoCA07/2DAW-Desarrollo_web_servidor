-- =====================================================================
-- Base de datos "dwes" para los apuntes de acceso a datos con PDO
-- Versión revisada: utf8mb4, usuario con permisos mínimos
-- Ejecutar como root (en phpMyAdmin: pestaña SQL, o desde consola:
--   mysql -u root -p < dwes.sql)
-- =====================================================================

DROP DATABASE IF EXISTS dwes;
CREATE DATABASE dwes CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE dwes;

CREATE TABLE tienda (
    cod     INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    tlf     VARCHAR(13)  NULL
) ENGINE=InnoDB;

CREATE TABLE familia (
    cod     VARCHAR(6)   NOT NULL PRIMARY KEY,
    nombre  VARCHAR(200) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE producto (
    cod           VARCHAR(12)   NOT NULL PRIMARY KEY,
    nombre        VARCHAR(200)  NULL,
    nombre_corto  VARCHAR(50)   NOT NULL UNIQUE,
    descripcion   TEXT          NULL,
    PVP           DECIMAL(10,2) NOT NULL,
    familia       VARCHAR(6)    NOT NULL,
    INDEX (familia),
    CONSTRAINT producto_familia FOREIGN KEY (familia) REFERENCES familia (cod)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE stock (
    producto  VARCHAR(12) NOT NULL,
    tienda    INT         NOT NULL,
    unidades  INT         NOT NULL,
    PRIMARY KEY (producto, tienda),
    CONSTRAINT stock_producto FOREIGN KEY (producto) REFERENCES producto (cod)
        ON UPDATE CASCADE,
    CONSTRAINT stock_tienda FOREIGN KEY (tienda) REFERENCES tienda (cod)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Usuario de la aplicación: solo puede leer y modificar datos de "dwes".
-- Se crea para 'localhost' y para '127.0.0.1' porque, según el sistema,
-- PHP se conecta por socket local o por TCP.
DROP USER IF EXISTS 'dwes'@'localhost', 'dwes'@'127.0.0.1';
CREATE USER 'dwes'@'localhost' IDENTIFIED BY 'abc123.';
CREATE USER 'dwes'@'127.0.0.1' IDENTIFIED BY 'abc123.';
GRANT SELECT, INSERT, UPDATE, DELETE ON dwes.* TO 'dwes'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON dwes.* TO 'dwes'@'127.0.0.1';
