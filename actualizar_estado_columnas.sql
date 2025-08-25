-- Script completo para actualizar columna Estado a VARCHAR(100) en todas las tablas
-- Copia y pega estos comandos en tu cliente MySQL (phpMyAdmin, MySQL Workbench, etc.)

-- Usar la base de datos
USE altas_bajas;

-- Actualizar tabla nobreak
ALTER TABLE nobreak MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla equipo  
ALTER TABLE equipo MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla videovigilancia
ALTER TABLE videovigilancia MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla telefonia
ALTER TABLE telefonia MODIFY COLUMN ESTADO VARCHAR(100) NOT NULL;

-- Actualizar tabla sonido
ALTER TABLE sonido MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla ram
ALTER TABLE ram MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla procesador
ALTER TABLE procesador MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla pila
ALTER TABLE pila MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla monitor
ALTER TABLE monitor MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla impresora
ALTER TABLE impresora MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla conectividad
ALTER TABLE conectividad MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla bateria
ALTER TABLE bateria MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla almacenamiento
ALTER TABLE almacenamiento MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla adaptador
ALTER TABLE adaptador MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Actualizar tabla microfono
ALTER TABLE microfono MODIFY COLUMN Estado VARCHAR(100) NOT NULL;

-- Verificar los cambios realizados
SELECT 
    TABLE_NAME as 'Tabla',
    COLUMN_NAME as 'Columna',
    DATA_TYPE as 'Tipo',
    CHARACTER_MAXIMUM_LENGTH as 'Longitud_Maxima'
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'altas_bajas' 
    AND COLUMN_NAME IN ('Estado', 'ESTADO')
ORDER BY TABLE_NAME;

-- Mensaje de confirmación
SELECT 'Actualización completada exitosamente!' as Resultado;
