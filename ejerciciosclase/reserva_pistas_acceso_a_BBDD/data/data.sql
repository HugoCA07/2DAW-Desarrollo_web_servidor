-- 1. Base de datos
DROP DATABASE IF EXISTS reservapistas;
CREATE DATABASE reservapistas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reservapistas;

-- 2. Usuario con permisos (coincide con tu PDO)
CREATE USER IF NOT EXISTS 'dwes'@'localhost' IDENTIFIED BY 'abc123.';
GRANT ALL PRIVILEGES ON reservapistas.* TO 'dwes'@'localhost';
FLUSH PRIVILEGES;

-- 3. Tabla reservas adaptada al CSV y a leerReservas()
DROP TABLE IF EXISTS reservas;
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL,
    email VARCHAR(150) NOT NULL,
    pista VARCHAR(50) NOT NULL,
    `date` DATE NOT NULL,
    hora TIME NOT NULL,
    material VARCHAR(255) DEFAULT '',
    normas VARCHAR(20) DEFAULT 'Confirmada',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_pista_fecha_hora UNIQUE (pista, `date`, hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Datos de prueba coherentes con las opciones de la app
INSERT INTO reservas (nombre, email, pista, `date`, hora, material, normas) VALUES
('Carlos Mendoza', 'carlos.mendoza@gmail.com', 'Pádel 1', '2026-10-21', '16:00:00', 'raquetas', 'Confirmada'),
('Laura Gómez', 'lau_gomez92@hotmail.com', 'Pádel 2', '2026-10-21', '17:00:00', 'raquetas, petos', 'Confirmada'),
('Marcos Silva', 'msilva_dev@proton.me', 'Tenis', '2026-10-21', '18:00:00', 'raquetas', 'Confirmada'),
('Elena Castillo', 'elena.castillo@empresa.es', 'Pádel 1', '2026-10-21', '19:00:00', '', 'Confirmada'),
('Javier Ortiz', 'javi_basket@yahoo.es', 'Baloncesto', '2026-10-21', '20:00:00', 'balon, petos', 'Confirmada'),

('Sara Ibáñez', 'sara.ibanez@outlook.com', 'Pádel 2', '2026-10-22', '16:00:00', 'raquetas', 'Confirmada'),
('Alejandro Ruiz', 'alex_ruiz_99@gmail.com', 'Tenis', '2026-10-22', '17:00:00', '', 'Confirmada'),
('Patricia Vega', 'pvega@icloud.com', 'Pádel 1', '2026-10-22', '18:00:00', 'raquetas', 'Confirmada'),
('Lucía Navarro', 'lucianavarro@live.com', 'Baloncesto', '2026-10-22', '19:00:00', 'balon', 'Confirmada'),

('Rubén Serrano', 'ruben_serrano@protonmail.com', 'Pádel 1', '2026-10-23', '17:00:00', 'raquetas', 'Confirmada'),
('Andrea Molina', 'andrea_moli@gmail.com', 'Tenis', '2026-10-23', '18:00:00', 'raquetas', 'Confirmada'),
('Hugo Romero', 'hromero.tech@outlook.es', 'Pádel 2', '2026-10-23', '20:00:00', '', 'Confirmada'),
('Daniel León', 'dani_leon@gmx.es', 'Baloncesto', '2026-10-24', '18:00:00', 'balon, petos', 'Confirmada'),
('Marta Domínguez', 'marta.dguez@colegio.edu.es', 'Pádel 1', '2026-10-24', '21:00:00', 'raquetas', 'Confirmada');