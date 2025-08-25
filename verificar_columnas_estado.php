<?php
/**
 * Script para verificar la estructura de columnas Estado/ESTADO en todas las tablas
 * y generar los comandos SQL correctos
 */

try {
    // Conexión a la base de datos
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICACIÓN DE COLUMNAS ESTADO ===\n\n";
    
    // Lista de tablas a verificar
    $tablas = [
        'nobreak', 'equipo', 'videovigilancia', 'telefonia', 'sonido', 
        'ram', 'procesador', 'pila', 'monitor', 'impresora', 
        'conectividad', 'bateria', 'almacenamiento', 'adaptador', 'microfono'
    ];
    
    $comandosSQL = [];
    $comandosSQL[] = "-- Comandos SQL generados automáticamente";
    $comandosSQL[] = "USE altas_bajas;";
    $comandosSQL[] = "";
    
    foreach ($tablas as $tabla) {
        echo "Verificando tabla: $tabla\n";
        
        // Verificar si la tabla existe y qué columnas de estado tiene
        $sql = "SHOW COLUMNS FROM `$tabla` LIKE '%estado%'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($columnas)) {
            echo "  ❌ No se encontró columna de estado\n";
            continue;
        }
        
        foreach ($columnas as $columna) {
            $nombreColumna = $columna['Field'];
            $tipoActual = $columna['Type'];
            $esNull = $columna['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
            $valorDefault = $columna['Default'] ? "DEFAULT '{$columna['Default']}'" : '';
            
            echo "  ✅ Columna encontrada: '$nombreColumna' ($tipoActual)\n";
            
            // Generar comando SQL apropiado
            if (strpos(strtolower($tipoActual), 'varchar') !== false) {
                $comando = "ALTER TABLE `$tabla` MODIFY COLUMN `$nombreColumna` VARCHAR(100) $esNull $valorDefault;";
                $comandosSQL[] = "-- Tabla: $tabla";
                $comandosSQL[] = $comando;
                $comandosSQL[] = "";
            }
        }
        echo "\n";
    }
    
    // Agregar comando de verificación
    $comandosSQL[] = "-- Verificar los cambios realizados";
    $comandosSQL[] = "SELECT";
    $comandosSQL[] = "    TABLE_NAME as 'Tabla',";
    $comandosSQL[] = "    COLUMN_NAME as 'Columna',";
    $comandosSQL[] = "    DATA_TYPE as 'Tipo',";
    $comandosSQL[] = "    CHARACTER_MAXIMUM_LENGTH as 'Longitud_Maxima',";
    $comandosSQL[] = "    IS_NULLABLE as 'Permite_NULL'";
    $comandosSQL[] = "FROM INFORMATION_SCHEMA.COLUMNS";
    $comandosSQL[] = "WHERE TABLE_SCHEMA = 'altas_bajas'";
    $comandosSQL[] = "    AND LOWER(COLUMN_NAME) LIKE '%estado%'";
    $comandosSQL[] = "ORDER BY TABLE_NAME, COLUMN_NAME;";
    
    // Guardar comandos en archivo
    $archivoSQL = __DIR__ . '/comandos_estado_corregidos.sql';
    file_put_contents($archivoSQL, implode("\n", $comandosSQL));
    
    echo "=== COMANDOS SQL GENERADOS ===\n\n";
    echo implode("\n", $comandosSQL);
    echo "\n\n✅ Comandos guardados en: $archivoSQL\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
