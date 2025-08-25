<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    echo "✅ Conexión exitosa\n";
    
    // Verificar algunas tablas específicas
    $tablas = ['nobreak', 'telefonia', 'equipo'];
    
    foreach ($tablas as $tabla) {
        try {
            $sql = "DESCRIBE `$tabla`";
            $stmt = $pdo->query($sql);
            $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "\n=== TABLA: $tabla ===\n";
            foreach ($columnas as $col) {
                if (stripos($col['Field'], 'estado') !== false) {
                    echo "Campo: {$col['Field']} | Tipo: {$col['Type']} | Null: {$col['Null']}\n";
                }
            }
        } catch (Exception $e) {
            echo "❌ Error en tabla $tabla: " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}
?>
