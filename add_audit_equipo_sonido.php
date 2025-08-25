<?php
// Script para verificar y agregar campos de auditoría a equipos de sonido

echo "=== SCRIPT DE AUDITORÍA PARA EQUIPOS DE SONIDO ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // Conexión a la base de datos
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado a la base de datos 'altas_bajas'\n\n";
    
    // Verificar si la tabla existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'equipo_sonido'");
    if (!$stmt->fetch()) {
        echo "❌ ERROR: La tabla 'equipo_sonido' no existe\n";
        exit(1);
    }
    echo "✅ Tabla 'equipo_sonido' encontrada\n";
    
    // Obtener la estructura actual de la tabla
    $stmt = $pdo->query("DESCRIBE equipo_sonido");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📋 Columnas actuales en 'equipo_sonido':\n";
    foreach ($columns as $column) {
        echo "   - $column\n";
    }
    echo "\n";
    
    // Verificar qué campos de auditoría faltan
    $audit_fields = [
        'fecha_creacion' => "ADD COLUMN fecha_creacion DATETIME DEFAULT NULL COMMENT 'Fecha de creación del registro'",
        'fecha_ultima_edicion' => "ADD COLUMN fecha_ultima_edicion DATETIME DEFAULT NULL COMMENT 'Fecha de última edición'",
        'ultimo_editor' => "ADD COLUMN ultimo_editor VARCHAR(100) DEFAULT NULL COMMENT 'Usuario que editó por última vez'"
    ];
    
    $fields_to_add = [];
    foreach ($audit_fields as $field => $sql) {
        if (!in_array($field, $columns)) {
            $fields_to_add[$field] = $sql;
        }
    }
    
    if (empty($fields_to_add)) {
        echo "✅ Todos los campos de auditoría ya existen en la tabla 'equipo_sonido'\n";
    } else {
        echo "📝 Campos de auditoría que se agregarán:\n";
        foreach ($fields_to_add as $field => $sql) {
            echo "   - $field\n";
        }
        
        echo "\n¿Desea continuar con la adición de campos? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        
        if (trim($line) === 'y' || trim($line) === 'Y') {
            foreach ($fields_to_add as $field => $sql) {
                try {
                    $pdo->exec("ALTER TABLE equipo_sonido $sql");
                    echo "✅ Campo '$field' agregado exitosamente\n";
                } catch (PDOException $e) {
                    echo "❌ Error agregando campo '$field': " . $e->getMessage() . "\n";
                }
            }
            
            // Verificar la estructura final
            echo "\n📋 Estructura final de la tabla 'equipo_sonido':\n";
            $stmt = $pdo->query("DESCRIBE equipo_sonido");
            $final_columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($final_columns as $col) {
                $null_info = $col['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
                $default_info = $col['Default'] !== null ? "DEFAULT '{$col['Default']}'" : '';
                echo "   {$col['Field']} {$col['Type']} $null_info $default_info\n";
            }
            
            echo "\n🎉 ¡Proceso completado!\n";
        } else {
            echo "❌ Operación cancelada por el usuario\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
