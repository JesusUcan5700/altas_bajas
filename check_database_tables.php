<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=sistema_inventario', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado a la base de datos: sistema_inventario\n";
    echo "================================================\n\n";
    
    // Obtener todas las tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "TABLAS ENCONTRADAS (" . count($tables) . "):\n";
    echo "------------------------\n";
    foreach ($tables as $table) {
        echo "✓ $table\n";
    }
    
    echo "\n\nVERIFICANDO TABLAS ESPERADAS:\n";
    echo "============================\n";
    
    $expectedTables = [
        'nobreak',
        'equipo', 
        'impresora',
        'monitor',
        'bateria',
        'almacenamiento',
        'ram',
        'sonido',
        'procesador',
        'conectividad',
        'telefonia',
        'videovigilancia',
        'adaptador'
    ];
    
    foreach ($expectedTables as $expectedTable) {
        if (in_array($expectedTable, $tables)) {
            echo "✅ $expectedTable - EXISTE\n";
        } else {
            echo "❌ $expectedTable - NO EXISTE\n";
        }
    }
    
    echo "\n\nESTRUCTURA DE TABLAS EXISTENTES:\n";
    echo "===============================\n";
    
    foreach ($tables as $table) {
        if (in_array($table, $expectedTables)) {
            echo "\n--- TABLA: $table ---\n";
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($columns as $column) {
                echo "  • {$column['Field']} ({$column['Type']})";
                if ($column['Key'] === 'PRI') echo " [PRIMARY KEY]";
                echo "\n";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
}
?>
