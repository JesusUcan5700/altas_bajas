<?php
/**
 * Script para actualizar la tabla monitor:
 * - Agregar campos de auditoría (fecha_creacion, fecha_ultima_edicion, ultimo_editor)
 * - Agregar campo EMISION_INVENTARIO
 * - Quitar campo fecha (solo usar EMISION_INVENTARIO)
 */

require_once 'common/config/main-local.php';

try {
    // Configuración de la base de datos
    $config = require 'common/config/main-local.php';
    $dbConfig = $config['components']['db'];
    
    $dsn = $dbConfig['dsn'];
    $username = $dbConfig['username'];
    $password = $dbConfig['password'];
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado a la base de datos exitosamente.\n";
    
    // Verificar si el campo EMISION_INVENTARIO ya existe
    $checkEmision = $pdo->query("SHOW COLUMNS FROM monitor LIKE 'EMISION_INVENTARIO'");
    if ($checkEmision->rowCount() == 0) {
        // Agregar campo EMISION_INVENTARIO
        $sql = "ALTER TABLE monitor ADD COLUMN EMISION_INVENTARIO DATE DEFAULT '2024-01-01' AFTER NUMERO_INVENTARIO";
        $pdo->exec($sql);
        echo "✓ Campo 'EMISION_INVENTARIO' agregado exitosamente.\n";
    } else {
        echo "- Campo 'EMISION_INVENTARIO' ya existe.\n";
    }
    
    // Verificar si los campos de auditoría ya existen
    $checkAuditoria = $pdo->query("SHOW COLUMNS FROM monitor LIKE 'fecha_creacion'");
    if ($checkAuditoria->rowCount() == 0) {
        // Agregar campos de auditoría
        $sql = "ALTER TABLE monitor 
                ADD COLUMN fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
                ADD COLUMN fecha_ultima_edicion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                ADD COLUMN ultimo_editor VARCHAR(100) DEFAULT 'Sistema'";
        $pdo->exec($sql);
        echo "✓ Campos de auditoría agregados exitosamente.\n";
    } else {
        echo "- Campos de auditoría ya existen.\n";
    }
    
    // Verificar si el campo fecha existe antes de eliminarlo
    $checkFecha = $pdo->query("SHOW COLUMNS FROM monitor LIKE 'fecha'");
    if ($checkFecha->rowCount() > 0) {
        // Eliminar campo fecha
        $sql = "ALTER TABLE monitor DROP COLUMN fecha";
        $pdo->exec($sql);
        echo "✓ Campo 'fecha' eliminado exitosamente.\n";
    } else {
        echo "- Campo 'fecha' ya no existe.\n";
    }
    
    // Mostrar estructura actualizada
    echo "\n=== Estructura actualizada de la tabla monitor ===\n";
    $result = $pdo->query("DESCRIBE monitor");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("%-25s %-20s %-10s %-10s %-15s %s\n", 
            $row['Field'], 
            $row['Type'], 
            $row['Null'], 
            $row['Key'], 
            $row['Default'], 
            $row['Extra']
        );
    }
    
    echo "\n✅ Actualización de tabla monitor completada exitosamente.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
