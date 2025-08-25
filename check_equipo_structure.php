<?php
// Script para verificar la estructura de la tabla equipo
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ESTRUCTURA DE LA TABLA EQUIPO ===\n";
    
    // Mostrar estructura de la tabla
    $stmt = $pdo->query("DESCRIBE equipo");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Campos de la tabla 'equipo':\n";
    foreach ($columns as $column) {
        echo "  - " . $column['Field'] . " (" . $column['Type'] . ")";
        if ($column['Null'] == 'NO') echo " NOT NULL";
        if ($column['Default'] !== null) echo " DEFAULT: " . $column['Default'];
        if ($column['Key'] == 'PRI') echo " PRIMARY KEY";
        if ($column['Key'] == 'UNI') echo " UNIQUE";
        echo "\n";
    }
    
    echo "\n=== VERIFICAR CAMPOS DE AUDITORÍA ===\n";
    $auditFields = ['ultimo_editor', 'fecha_ultima_edicion', 'created_at', 'updated_at', 'modified_by'];
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
    
    echo "\n=== ÚLTIMO EQUIPO MODIFICADO ===\n";
    
    // Verificar si hay algún campo de fecha de modificación
    $timestampFields = [];
    foreach ($columns as $column) {
        if (strpos(strtolower($column['Field']), 'fecha') !== false || 
            strpos(strtolower($column['Field']), 'time') !== false ||
            strpos(strtolower($column['Field']), 'created') !== false ||
            strpos(strtolower($column['Field']), 'updated') !== false ||
            strpos(strtolower($column['Field']), 'modified') !== false) {
            $timestampFields[] = $column['Field'];
        }
    }
    
    if (empty($timestampFields)) {
        echo "⚠️  No se encontraron campos de timestamp/fecha en la tabla\n";
        echo "📋 Solo se puede usar el ID más alto como referencia del último registro:\n";
        
        $stmt = $pdo->query("SELECT * FROM equipo ORDER BY idEQUIPO DESC LIMIT 1");
        $lastEquipo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($lastEquipo) {
            echo "🆔 Último equipo por ID: " . $lastEquipo['idEQUIPO'] . "\n";
            echo "🏷️  Marca: " . ($lastEquipo['MARCA'] ?? 'N/A') . "\n";
            echo "📱 Modelo: " . ($lastEquipo['MODELO'] ?? 'N/A') . "\n";
            echo "📅 Emisión Inventario: " . ($lastEquipo['EMISION_INVENTARIO'] ?? 'N/A') . "\n";
        }
    } else {
        echo "📅 Campos de fecha/timestamp encontrados:\n";
        foreach ($timestampFields as $field) {
            echo "  - $field\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
