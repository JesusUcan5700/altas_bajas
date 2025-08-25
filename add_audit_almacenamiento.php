<?php
// Script para verificar y agregar campos de auditoría a almacenamiento
try {
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    echo "Conexión PDO exitosa\n";
    
    // Verificar estructura de la tabla almacenamiento
    $stmt = $pdo->query("SHOW COLUMNS FROM almacenamiento");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Campos existentes en almacenamiento: " . implode(', ', $columns) . "\n\n";
    
    // Verificar si los campos de auditoría ya existen
    $auditFields = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    $fieldsToAdd = [];
    
    foreach ($auditFields as $field) {
        if (!in_array($field, $columns)) {
            $fieldsToAdd[] = $field;
        }
    }
    
    if (!empty($fieldsToAdd)) {
        echo "Campos faltantes: " . implode(', ', $fieldsToAdd) . "\n";
        
        // Agregar campos faltantes
        foreach ($fieldsToAdd as $field) {
            if ($field === 'ultimo_editor') {
                $sql = "ALTER TABLE almacenamiento ADD COLUMN $field VARCHAR(100) NULL";
            } else {
                $sql = "ALTER TABLE almacenamiento ADD COLUMN $field TIMESTAMP NULL";
            }
            echo "Agregando campo $field...\n";
            $pdo->exec($sql);
        }
        
        // Actualizar registros existentes
        echo "Actualizando registros existentes...\n";
        $pdo->exec("UPDATE almacenamiento SET fecha_creacion = NOW(), fecha_ultima_edicion = NOW(), ultimo_editor = 'Sistema' WHERE fecha_creacion IS NULL");
        
        echo "¡Campos de auditoría agregados exitosamente!\n";
    } else {
        echo "Todos los campos de auditoría ya existen.\n";
    }
    
    // Mostrar ejemplo de registro
    $stmt = $pdo->query("SELECT * FROM almacenamiento LIMIT 1");
    $example = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($example) {
        echo "\nEjemplo de registro:\n";
        echo "ID: " . ($example['idAlmacenamiento'] ?? 'N/A') . "\n";
        echo "Marca: " . ($example['MARCA'] ?? 'N/A') . "\n";
        echo "Fecha creación: " . ($example['fecha_creacion'] ?? 'NULL') . "\n";
        echo "Último editor: " . ($example['ultimo_editor'] ?? 'NULL') . "\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
