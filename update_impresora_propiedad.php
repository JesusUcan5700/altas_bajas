<?php
/**
 * Script para actualizar la tabla impresora:
 * - Agregar campo propia_rentada
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
    
    // Verificar si el campo propia_rentada ya existe
    $checkColumn = $pdo->query("SHOW COLUMNS FROM impresora LIKE 'propia_rentada'");
    if ($checkColumn->rowCount() == 0) {
        // Agregar campo propia_rentada
        $sql = "ALTER TABLE impresora ADD COLUMN propia_rentada ENUM('propia', 'rentada') DEFAULT 'propia' AFTER Estado";
        $pdo->exec($sql);
        echo "✓ Campo 'propia_rentada' agregado exitosamente.\n";
    } else {
        echo "- Campo 'propia_rentada' ya existe.\n";
    }
    
    // Verificar si el campo fecha existe antes de eliminarlo
    $checkFecha = $pdo->query("SHOW COLUMNS FROM impresora LIKE 'fecha'");
    if ($checkFecha->rowCount() > 0) {
        // Eliminar campo fecha
        $sql = "ALTER TABLE impresora DROP COLUMN fecha";
        $pdo->exec($sql);
        echo "✓ Campo 'fecha' eliminado exitosamente.\n";
    } else {
        echo "- Campo 'fecha' ya no existe.\n";
    }
    
    // Mostrar estructura actualizada
    echo "\n=== Estructura actualizada de la tabla impresora ===\n";
    $result = $pdo->query("DESCRIBE impresora");
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
    
    echo "\n✅ Actualización de tabla impresora completada exitosamente.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
