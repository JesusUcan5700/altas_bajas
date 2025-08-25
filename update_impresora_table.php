<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== AGREGANDO CAMPOS FALTANTES A TABLA IMPRESORA ===\n";
    
    // Verificar estructura actual
    $stmt = $pdo->query("DESCRIBE impresora");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $existingFields = array_column($columns, 'Field');
    
    $fieldsToAdd = [
        'TIPO' => 'VARCHAR(45) DEFAULT "Inyección de Tinta"',
        'EMISION_INVENTARIO' => 'VARCHAR(45) DEFAULT "2024-01-01"',
        'fecha_creacion' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
        'fecha_ultima_edicion' => 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'ultimo_editor' => 'VARCHAR(100) DEFAULT "Sistema"'
    ];
    
    foreach ($fieldsToAdd as $fieldName => $fieldDefinition) {
        if (!in_array($fieldName, $existingFields)) {
            $sql = "ALTER TABLE impresora ADD COLUMN $fieldName $fieldDefinition";
            echo "📝 Ejecutando: $sql\n";
            $pdo->exec($sql);
            echo "✅ Campo '$fieldName' agregado correctamente\n";
        } else {
            echo "⚠️  Campo '$fieldName' ya existe\n";
        }
    }
    
    echo "\n=== ACTUALIZAR DATOS EXISTENTES ===\n";
    
    // Actualizar registros existentes con datos por defecto
    $updateSql = "UPDATE impresora SET 
                    TIPO = COALESCE(TIPO, 'Inyección de Tinta'),
                    EMISION_INVENTARIO = COALESCE(EMISION_INVENTARIO, fecha),
                    fecha_creacion = COALESCE(fecha_creacion, NOW()),
                    fecha_ultima_edicion = COALESCE(fecha_ultima_edicion, NOW()),
                    ultimo_editor = COALESCE(ultimo_editor, 'Sistema')
                  WHERE TIPO IS NULL OR ultimo_editor IS NULL";
    
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute();
    echo "✅ Datos actualizados en " . $stmt->rowCount() . " registros\n";
    
    echo "\n=== VERIFICAR RESULTADO ===\n";
    $stmt = $pdo->query("SELECT idIMPRESORA, MARCA, MODELO, TIPO, EMISION_INVENTARIO, fecha_ultima_edicion, ultimo_editor FROM impresora LIMIT 3");
    $impresoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($impresoras as $impresora) {
        echo "ID: {$impresora['idIMPRESORA']} | {$impresora['MARCA']} {$impresora['MODELO']} | Tipo: {$impresora['TIPO']} | Editor: {$impresora['ultimo_editor']}\n";
    }
    
    echo "\n✅ CAMPOS AGREGADOS CORRECTAMENTE A TABLA IMPRESORA\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
