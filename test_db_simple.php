<?php
// Prueba simple de conexión a base de datos
echo "=== PRUEBA DE CONEXIÓN ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // Probar conexión básica
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    echo "✅ Conexión a MySQL: OK\n";
    
    // Listar bases de datos
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Bases de datos disponibles:\n";
    foreach ($databases as $db) {
        echo "   - $db\n";
    }
    
    // Probar conexión a altas_bajas
    try {
        $pdo_app = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
        echo "\n✅ Conexión a 'altas_bajas': OK\n";
        
        // Verificar tabla nobreak
        $stmt = $pdo_app->query("SHOW TABLES LIKE 'nobreak'");
        $exists = $stmt->fetch();
        
        if ($exists) {
            echo "✅ Tabla 'nobreak': EXISTE\n";
            
            // Contar registros
            $stmt = $pdo_app->query("SELECT COUNT(*) FROM nobreak");
            $count = $stmt->fetchColumn();
            echo "📊 Registros en 'nobreak': $count\n";
            
            if ($count > 0) {
                // Mostrar primeros 3 registros
                echo "\n📝 Primeros registros:\n";
                $stmt = $pdo_app->query("SELECT * FROM nobreak LIMIT 3");
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($records as $i => $record) {
                    echo "   Registro " . ($i + 1) . ":\n";
                    foreach ($record as $key => $value) {
                        echo "     $key: " . ($value ?: 'NULL') . "\n";
                    }
                    echo "\n";
                }
            }
            
        } else {
            echo "❌ Tabla 'nobreak': NO EXISTE\n";
            
            // Listar tablas existentes
            $stmt = $pdo_app->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "📋 Tablas existentes en 'altas_bajas':\n";
            foreach ($tables as $table) {
                echo "   - $table\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error conectando a 'altas_bajas': " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error de conexión MySQL: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE PRUEBA ===\n";
?>
