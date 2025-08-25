-- Script para agregar columnas de seguimiento de ediciones
-- Ejecutar en la base de datos de equipos

USE altas_bajas; -- Ajustar el nombre de la base de datos según corresponda

-- Agregar columnas para rastrear el último editor y fecha de edición
ALTER TABLE equipo 
ADD COLUMN ultimo_editor VARCHAR(100) NULL AFTER descripcion,
ADD COLUMN fecha_ultima_edicion DATETIME NULL AFTER ultimo_editor;

-- Agregar comentarios para documentar las columnas
ALTER TABLE equipo 
MODIFY COLUMN ultimo_editor VARCHAR(100) NULL COMMENT 'Usuario que realizó la última edición del equipo',
MODIFY COLUMN fecha_ultima_edicion DATETIME NULL COMMENT 'Fecha y hora de la última edición del equipo';

-- Opcional: Agregar índice para mejorar consultas por fecha de edición
CREATE INDEX idx_fecha_ultima_edicion ON equipo(fecha_ultima_edicion);

-- Verificar que las columnas se agregaron correctamente
-- DESCRIBE equipo;
