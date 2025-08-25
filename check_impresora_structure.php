<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ESTRUCTURA DE LA TABLA IMPRESORA ===\n";
    
    // Mostrar estructura de la tabla
    $stmt = $pdo->query("DESCRIBE impresora");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Campos de la tabla 'impresora':\n";
    foreach ($columns as $column) {
        echo "  - " . $column['Field'] . " (" . $column['Type'] . ")";
        if ($column['Null'] == 'NO') echo " NOT NULL";
        if ($column['Default'] !== null) echo " DEFAULT: " . $column['Default'];
        if ($column['Key'] == 'PRI') echo " PRIMARY KEY";
        if ($column['Key'] == 'UNI') echo " UNIQUE";
        echo "\n";
    }
    
    echo "\n=== VERIFICAR CAMPOS DE AUDITORÍA Y TIEMPO ===\n";
    $auditFields = ['ultimo_editor', 'fecha_ultima_edicion', 'fecha_creacion', 'EMISION_INVENTARIO', 'TIPO'];
    foreach ($auditFields as $field) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $field) {
                echo "✅ Campo '$field' EXISTE: " . $column['Type'] . "\n";
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "❌ Campo '$field' NO EXISTE\n";
        }
    }
    
    echo "\n=== MOSTRAR ALGUNOS REGISTROS EJEMPLO ===\n";
    $stmt = $pdo->query("SELECT * FROM impresora LIMIT 3");
    $impresoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($impresoras)) {
        foreach ($impresoras as $index => $impresora) {
            echo "Impresora " . ($index + 1) . ":\n";
            foreach ($impresora as $field => $value) {
                echo "  $field: " . (strlen($value) > 50 ? substr($value, 0, 47) . "..." : $value) . "\n";
            }
            echo "\n";
        }
    } else {
        echo "No hay impresoras registradas\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
