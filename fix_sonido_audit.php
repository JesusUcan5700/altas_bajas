<?php
// Script para verificar y agregar campos de auditoría faltantes

echo "=== VERIFICACIÓN DE CAMPOS DE AUDITORÍA ===\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    echo "✅ Conectado a la base de datos\n";
    
    echo "\nVerificando tabla equipo_sonido...\n";
    $stmt = $pdo->query('DESCRIBE equipo_sonido');
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Columnas encontradas:\n";
    foreach ($columns as $col) {
        echo "- $col\n";
    }
    
    $audit_fields = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    $missing = array_diff($audit_fields, $columns);
    
    if (!empty($missing)) {
        echo "\n❌ Campos faltantes: " . implode(', ', $missing) . "\n";
        echo "Agregando campos...\n";
        
        foreach ($missing as $field) {
            if ($field === 'ultimo_editor') {
                $sql = "ALTER TABLE equipo_sonido ADD COLUMN $field VARCHAR(100) DEFAULT NULL";
            } else {
                $sql = "ALTER TABLE equipo_sonido ADD COLUMN $field DATETIME DEFAULT NULL";
            }
            
            $pdo->exec($sql);
            echo "✅ $field agregado\n";
        }
        
        echo "\n🎉 Todos los campos de auditoría han sido agregados exitosamente!\n";
    } else {
        echo "\n✅ Todos los campos de auditoría ya existen\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
