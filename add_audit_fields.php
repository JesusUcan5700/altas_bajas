<?php
// Script para agregar campos de auditoría a la tabla equipo
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== AGREGAR CAMPOS DE AUDITORÍA A LA TABLA EQUIPO ===\n";
    
    // Verificar si los campos ya existen
    $stmt = $pdo->query("DESCRIBE equipo");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $existingFields = array_column($columns, 'Field');
    
    $fieldsToAdd = [
        'fecha_creacion' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
        'fecha_ultima_edicion' => 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'ultimo_editor' => 'VARCHAR(100) DEFAULT "Sistema"'
    ];
    
    foreach ($fieldsToAdd as $fieldName => $fieldDefinition) {
        if (!in_array($fieldName, $existingFields)) {
            $sql = "ALTER TABLE equipo ADD COLUMN $fieldName $fieldDefinition";
            echo "📝 Ejecutando: $sql\n";
            $pdo->exec($sql);
            echo "✅ Campo '$fieldName' agregado correctamente\n";
        } else {
            echo "⚠️  Campo '$fieldName' ya existe\n";
        }
    }
    
    echo "\n=== ACTUALIZAR DATOS EXISTENTES ===\n";
    
    // Actualizar registros existentes con datos por defecto
    $updateSql = "UPDATE equipo SET 
                    fecha_creacion = COALESCE(fecha_creacion, NOW()),
                    fecha_ultima_edicion = COALESCE(fecha_ultima_edicion, NOW()),
                    ultimo_editor = COALESCE(ultimo_editor, 'Sistema')
                  WHERE fecha_creacion IS NULL OR ultimo_editor IS NULL";
    
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute();
    echo "✅ Datos actualizados en " . $stmt->rowCount() . " registros\n";
    
    echo "\n=== VERIFICAR RESULTADO ===\n";
    $stmt = $pdo->query("SELECT idEQUIPO, MARCA, MODELO, fecha_creacion, fecha_ultima_edicion, ultimo_editor FROM equipo ORDER BY fecha_ultima_edicion DESC LIMIT 3");
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($equipos as $equipo) {
        echo "ID: {$equipo['idEQUIPO']} | {$equipo['MARCA']} {$equipo['MODELO']} | Editado: {$equipo['fecha_ultima_edicion']} | Por: {$equipo['ultimo_editor']}\n";
    }
    
    echo "\n✅ CAMPOS DE AUDITORÍA CONFIGURADOS CORRECTAMENTE\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
