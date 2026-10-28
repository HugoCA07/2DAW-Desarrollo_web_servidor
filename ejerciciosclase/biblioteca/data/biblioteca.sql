-- biblioteca.sql — Base de datos de partida para la práctica de PDO
DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca;

CREATE TABLE libros (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    isbn        VARCHAR(17)  NOT NULL UNIQUE,
    titulo      VARCHAR(150) NOT NULL,
    autor       VARCHAR(100) NOT NULL,
    anio        SMALLINT     NOT NULL,
    genero      VARCHAR(30)  NOT NULL,
    disponible  TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE prestamos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    libro_id    INT          NOT NULL,
    alumno      VARCHAR(100) NOT NULL,
    fecha       DATE         NOT NULL,
    CONSTRAINT fk_prestamo_libro FOREIGN KEY (libro_id) REFERENCES libros(id)
) ENGINE=InnoDB;

INSERT INTO libros (isbn, titulo, autor, anio, genero, disponible) VALUES
('978-84-376-0494-7', 'Cien años de soledad',         'Gabriel García Márquez', 1967, 'Novela',  0),
('978-84-206-5131-1', 'La casa de Bernarda Alba',     'Federico García Lorca',  1945, 'Teatro',  1),
('978-84-670-3355-5', 'Nada',                         'Carmen Laforet',         1945, 'Novela',  1),
('978-84-08-04364-5', 'Sapiens',                      'Yuval Noah Harari',      2011, 'Ensayo',  1),
('978-84-376-0040-6', 'Veinte poemas de amor',        'Pablo Neruda',           1924, 'Poesía',  0),
('978-84-9759-252-2', 'Mortadelo y Filemón: El sulfato atómico', 'Francisco Ibáñez', 1969, 'Cómic', 1),
('978-84-204-2873-9', 'Luces de bohemia',             'Ramón María del Valle-Inclán', 1924, 'Teatro', 1),
('978-84-339-6122-2', 'El infinito en un junco',      'Irene Vallejo',          2019, 'Ensayo',  1),
('978-84-376-0128-1', 'Campos de Castilla',           'Antonio Machado',        1912, 'Poesía',  1),
('978-84-670-6352-1', 'La sombra del viento',         'Carlos Ruiz Zafón',      2001, 'Novela',  1);

-- Préstamos: los libros 1 y 5 no se podrán borrar (clave foránea, error 1451)
INSERT INTO prestamos (libro_id, alumno, fecha) VALUES
(1, 'Ana López',  '2026-10-20'),
(5, 'Luis Pérez', '2026-10-22'),
(1, 'Marta Ruiz', '2026-09-15');
