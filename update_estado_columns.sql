-- Script para actualizar el tamaño de la columna Estado en todas las tablas
-- Aumentando de VARCHAR(10) a VARCHAR(100) para permitir estados más largos

-- Tabla nobreak
ALTER TABLE nobreak MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla equipo  
ALTER TABLE equipo MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla videovigilancia
ALTER TABLE videovigilancia MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla telefonia
ALTER TABLE telefonia MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla sonido
ALTER TABLE sonido MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla ram
ALTER TABLE ram MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla procesador
ALTER TABLE procesador MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla pila
ALTER TABLE pila MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla monitor
ALTER TABLE monitor MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla impresora
ALTER TABLE impresora MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla conectividad
ALTER TABLE conectividad MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla bateria
ALTER TABLE bateria MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla almacenamiento
ALTER TABLE almacenamiento MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla adaptador
ALTER TABLE adaptador MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Tabla microfono
ALTER TABLE microfono MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Verificar los cambios
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
    AND COLUMN_NAME = 'Estado'
ORDER BY TABLE_NAME;
