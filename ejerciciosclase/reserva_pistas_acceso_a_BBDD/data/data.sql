-- 1. Crear base de datos
DROP DATABASE IF EXISTS reservapistas;
CREATE DATABASE reservapistas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reservapistas;

-- 2. Crear usuario dwes y darle permisos (el que tienes en el PDO)
CREATE USER IF NOT EXISTS 'dwes'@'localhost' IDENTIFIED BY 'abc123.';
GRANT ALL PRIVILEGES ON reservapistas.* TO 'dwes'@'localhost';
FLUSH PRIVILEGES;

-- 3. Crear tabla reservas
DROP TABLE IF EXISTS reservas;
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    pista VARCHAR(50) NOT NULL,
    `date` DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    precio DECIMAL(6,2) DEFAULT 15.00,
    estado ENUM('confirmada', 'pendiente', 'cancelada') DEFAULT 'confirmada',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Inserción de reservas de prueba (variedad de pistas, fechas y correos)
INSERT INTO reservas (nombre, email, telefono, pista, `date`, hora_inicio, hora_fin, precio, estado) VALUES
('Carlos Mendoza', 'carlos.mendoza@gmail.com', '611223344', 'Pádel 1', '2026-10-21', '09:00:00', '10:30:00', 18.00, 'confirmada'),
('Laura Gómez', 'lau_gomez92@hotmail.com', '622334455', 'Pádel 2', '2026-10-21', '10:30:00', '12:00:00', 18.00, 'confirmada'),
('Marcos Silva', 'msilva_dev@proton.me', '633445566', 'Tenis 1', '2026-10-21', '11:00:00', '12:30:00', 14.00, 'confirmada'),
('Elena Castillo', 'elena.castillo@empresa.es', '644556677', 'Pádel 1', '2026-10-21', '18:00:00', '19:30:00', 20.00, 'confirmada'),
('Javier Ortiz', 'javi_basket@yahoo.es', '655667788', 'Fútbol 7', '2026-10-21', '20:00:00', '21:30:00', 45.00, 'pendiente'),

('Sara Ibáñez', 'sara.ibanez@outlook.com', '666778899', 'Pádel 2', '2026-10-22', '09:30:00', '11:00:00', 18.00, 'confirmada'),
('Alejandro Ruiz', 'alex_ruiz_99@gmail.com', '677889900', 'Tenis 2', '2026-10-22', '10:00:00', '11:30:00', 14.00, 'cancelada'),
('Patricia Vega', 'pvega@icloud.com', '688990011', 'Pádel 1', '2026-10-22', '17:00:00', '18:30:00', 20.00, 'confirmada'),
('David Morales', 'dmorales.work@gmail.com', '699001122', 'Frontón', '2026-10-22', '18:30:00', '19:30:00', 10.00, 'confirmada'),
('Lucía Navarro', 'lucianavarro@live.com', '610112233', 'Fútbol 7', '2026-10-22', '19:30:00', '21:00:00', 45.00, 'confirmada'),

('Rubén Serrano', 'ruben_serrano@protonmail.com', '621223344', 'Pádel 1', '2026-10-23', '08:30:00', '10:00:00', 16.00, 'confirmada'),
('Andrea Molina', 'andrea_moli@gmail.com', '632334455', 'Tenis 1', '2026-10-23', '12:00:00', '13:30:00', 14.00, 'pendiente'),
('Hugo Romero', 'hromero.tech@outlook.es', '643445566', 'Pádel 2', '2026-10-23', '16:30:00', '18:00:00', 20.00, 'confirmada'),
('Cristina Gil', 'cris.gil95@gmail.com', '654556677', 'Frontón', '2026-10-23', '18:00:00', '19:00:00', 10.00, 'confirmada'),
('Iván Garrido', 'igarrido_sport@hotmail.com', '665667788', 'Pádel 1', '2026-10-23', '19:30:00', '21:00:00', 22.00, 'confirmada'),

('Nuria Peña', 'nuria.pena@gmail.com', '676778899', 'Tenis 2', '2026-10-24', '10:00:00', '11:30:00', 16.00, 'confirmada'),
('Daniel León', 'dani_leon@gmx.es', '687889900', 'Pádel 2', '2026-10-24', '11:30:00', '13:00:00', 22.00, 'confirmada'),
('Marta Domínguez', 'marta.dguez@colegio.edu.es', '698990011', 'Fútbol 7', '2026-10-24', '17:00:00', '18:30:00', 50.00, 'confirmada');