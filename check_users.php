<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== BUSCANDO TABLA DE USUARIOS ===\n";
    
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tablas disponibles:\n";
    $userTables = [];
    
    foreach ($tables as $table) {
        echo "- $table\n";
        if (stripos($table, 'user') !== false) {
            $userTables[] = $table;
            echo "  ^^^ TABLA DE USUARIOS ENCONTRADA ^^^\n";
        }
    }
    
    if (!empty($userTables)) {
        echo "\n=== ESTRUCTURA DE TABLAS DE USUARIOS ===\n";
        foreach ($userTables as $table) {
            echo "\nTabla: $table\n";
            $columns = $pdo->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($columns as $column) {
                echo "  - " . $column['Field'] . " (" . $column['Type'] . ")\n";
            }
            
            // Ver algunos registros de ejemplo
            $sample = $pdo->query("SELECT * FROM $table LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($sample)) {
                echo "\nRegistros de ejemplo:\n";
                foreach ($sample as $index => $row) {
                    echo "  Registro " . ($index + 1) . ":\n";
                    foreach ($row as $field => $value) {
                        if (strlen($value) > 50) $value = substr($value, 0, 47) . "...";
                        echo "    $field: $value\n";
                    }
                    echo "\n";
                }
            }
        }
    } else {
        echo "\n❌ No se encontraron tablas de usuarios\n";
        echo "💡 Necesitarías crear un sistema de autenticación o usar el sistema básico de Yii2\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
