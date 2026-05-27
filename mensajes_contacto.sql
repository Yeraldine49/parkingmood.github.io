-- ================================================================
-- PARKING CONTROL — Tabla: mensajes_contacto
-- ================================================================
-- Ejecuta este script en tu base de datos "parking_control"
-- si ya tienes las demás tablas creadas y solo necesitas agregar
-- la tabla de mensajes del formulario de contacto público.
-- ================================================================

USE parking_control;

CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL              COMMENT 'Nombre del visitante',
    correo      VARCHAR(100)  NOT NULL              COMMENT 'Correo del visitante (se usa como Reply-To)',
    asunto      VARCHAR(200)  DEFAULT NULL          COMMENT 'Asunto del mensaje',
    mensaje     TEXT          NOT NULL              COMMENT 'Cuerpo del mensaje',
    leido       TINYINT(1)    NOT NULL DEFAULT 0    COMMENT '0 = no leído, 1 = leído (para panel admin)',
    recibido_en TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Índice para consultas rápidas por correo y estado de lectura
-- ----------------------------------------------------------------
CREATE INDEX idx_correo ON mensajes_contacto (correo);
CREATE INDEX idx_leido  ON mensajes_contacto (leido);

-- ----------------------------------------------------------------
-- Ejemplo de cómo se verán los datos insertados:
-- ----------------------------------------------------------------
-- INSERT INTO mensajes_contacto (nombre, correo, asunto, mensaje) VALUES
-- ('Pedro García', 'pedro@gmail.com', 'Consulta de tarifas',
--  '¿Cuánto cuesta dejar una camioneta por 3 horas?');
