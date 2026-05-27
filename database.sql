-- ============================================
-- PARKING CONTROL - Script de Base de Datos
-- ============================================

CREATE DATABASE IF NOT EXISTS parking_control CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE parking_control;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('Administrador','Trabajador','Cliente') NOT NULL DEFAULT 'Cliente',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de tarifas
CREATE TABLE IF NOT EXISTS tarifas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_vehiculo VARCHAR(50) NOT NULL,
    precio_hora DECIMAL(10,2) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de vehículos (entradas)
CREATE TABLE IF NOT EXISTS entradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placas VARCHAR(20) NOT NULL,
    tipo_vehiculo VARCHAR(50) NOT NULL,
    hora_entrada DATETIME NOT NULL,
    hora_salida DATETIME DEFAULT NULL,
    tiempo_total VARCHAR(50) DEFAULT NULL,
    costo DECIMAL(10,2) DEFAULT NULL,
    estado ENUM('dentro','salida') DEFAULT 'dentro',
    registrado_por INT,
    FOREIGN KEY (registrado_por) REFERENCES usuarios(id)
);

-- ============================================================
-- Tabla de mensajes de contacto (visitantes desde index.php)
-- Guarda nombre, correo y mensaje de cualquier persona que
-- llene el formulario "Contáctanos" de la página principal.
-- ============================================================
CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    asunto VARCHAR(200) DEFAULT NULL,
    mensaje TEXT NOT NULL,
    leido TINYINT(1) NOT NULL DEFAULT 0,
    recibido_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Datos iniciales
INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES
('Admin',        'admin@parking.com',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador'),
('Juan Pérez',   'trabajador@parking.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trabajador'),
('María López',  'cliente@parking.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cliente');

-- Nota: La contraseña para todos los usuarios es "password"

INSERT INTO tarifas (tipo_vehiculo, precio_hora) VALUES
('Auto', 20.00),
('Moto', 10.00),
('Camioneta', 25.00);

INSERT INTO entradas (placas, tipo_vehiculo, hora_entrada, registrado_por) VALUES
('ABC-1234', 'Auto', NOW() - INTERVAL 2 HOUR, 1),
('XYZ-5678', 'Moto', NOW() - INTERVAL 1 HOUR, 2),
('DEF-9012', 'Auto', NOW() - INTERVAL 30 MINUTE, 1);
