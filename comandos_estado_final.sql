-- Script SQL para actualizar columnas Estado/ESTADO a VARCHAR(100)
-- Incluye variaciones en mayúsculas y minúsculas
-- Ejecutar uno por uno para evitar errores

USE altas_bajas;

-- ===== COMANDOS INDIVIDUALES - EJECUTAR UNO POR UNO =====

-- Tabla nobreak (Estado)
ALTER TABLE nobreak MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla equipo (Estado)  
ALTER TABLE equipo MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla videovigilancia (ESTADO o Estado)
-- Probar primero con ESTADO
ALTER TABLE videovigilancia MODIFY COLUMN ESTADO VARCHAR(100) NOT NULL;
-- Si falla, probar con Estado
-- ALTER TABLE videovigilancia MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla telefonia (ESTADO)
ALTER TABLE telefonia MODIFY COLUMN ESTADO VARCHAR(100) NOT NULL;

-- Tabla sonido (Estado)
ALTER TABLE sonido MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla ram (Estado)
ALTER TABLE ram MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla procesador (Estado)
ALTER TABLE procesador MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla pila (Estado)
ALTER TABLE pila MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla monitor (Estado)
ALTER TABLE monitor MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla impresora (Estado)
ALTER TABLE impresora MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla conectividad (Estado)
ALTER TABLE conectividad MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla bateria (Estado)
ALTER TABLE bateria MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla almacenamiento (Estado)
ALTER TABLE almacenamiento MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla adaptador (Estado)
ALTER TABLE adaptador MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla microfono (Estado)
ALTER TABLE microfono MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- ===== VERIFICACIÓN FINAL =====
-- Ejecutar después de todos los ALTER TABLE
SELECT 
    TABLE_NAME as 'Tabla',
    COLUMN_NAME as 'Columna',
    DATA_TYPE as 'Tipo',
    CHARACTER_MAXIMUM_LENGTH as 'Longitud'
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'altas_bajas' 
    AND (COLUMN_NAME = 'Estado' OR COLUMN_NAME = 'ESTADO')
ORDER BY TABLE_NAME;

-- ===== COMANDOS ALTERNATIVOS SI ALGUNO FALLA =====
-- Si algún comando falla, probar estas variaciones:

-- Para videovigilancia si ESTADO no funciona:
-- ALTER TABLE videovigilancia MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Para verificar qué columnas existen en una tabla específica:
-- DESCRIBE nobreak;
-- DESCRIBE telefonia;
-- DESCRIBE videovigilancia;
