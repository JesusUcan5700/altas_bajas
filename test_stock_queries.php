<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=sistema_inventario', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Probando las consultas de stock por categoría...\n\n";
    
    // Definir las tablas que existen realmente
    $tablas = [
        'nobreak' => 'Estado',
        'equipo' => 'Estado', 
        'impresora' => 'Estado',
        'monitor' => 'ESTADO',
        'baterias' => 'estado',
        'almacenamiento' => 'ESTADO',
        'memoria_ram' => 'estado',
        'equipo_sonido' => 'estado',
        'procesadores' => 'estado',
        'conectividad' => 'Estado',
        'telefonia' => 'ESTADO',
        'video_vigilancia' => 'estado',
        'adaptadores' => 'estado'
    ];
    
    foreach ($tablas as $tabla => $campoEstado) {
        echo "📊 Tabla: $tabla\n";
        echo "   Campo estado: $campoEstado\n";
        
        try {
            // Verificar si la tabla existe
            $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
            if ($stmt->rowCount() == 0) {
                echo "   ❌ Tabla no existe\n\n";
                continue;
            }
            
            // Verificar si el campo existe
            $stmt = $pdo->query("SHOW COLUMNS FROM $tabla LIKE '$campoEstado'");
            if ($stmt->rowCount() == 0) {
                // Buscar campos alternativos
                $camposAlt = ['estado', 'Estado', 'ESTADO', 'status'];
                $campoEncontrado = false;
                
                foreach ($camposAlt as $alt) {
                    $stmt = $pdo->query("SHOW COLUMNS FROM $tabla LIKE '$alt'");
                    if ($stmt->rowCount() > 0) {
                        $campoEstado = $alt;
                        $campoEncontrado = true;
                        echo "   🔄 Campo estado encontrado: $alt\n";
                        break;
                    }
                }
                
                if (!$campoEncontrado) {
                    echo "   ❌ Campo estado no encontrado\n\n";
                    continue;
                }
            }
            
            // Hacer la consulta
            $sql = "
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN $campoEstado = 'Activo' THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN $campoEstado IN ('Inactivo', 'Disponible', 'Inactivo(Sin Asignar)') THEN 1 ELSE 0 END) as disponibles
                FROM $tabla
            ";
            
            $stmt = $pdo->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "   ✅ Total: {$resultado['total']}, Activos: {$resultado['activos']}, Disponibles: {$resultado['disponibles']}\n\n";
            
        } catch (Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}
?>
