<?php
// Script para inicializar campos de auditoría en equipos de sonido existentes

echo "=== INICIALIZACIÓN DE CAMPOS DE AUDITORÍA ===\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado a la base de datos\n";
    
    // Verificar registros sin campos de auditoría
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM equipo_sonido WHERE fecha_creacion IS NULL OR fecha_ultima_edicion IS NULL");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $result['total'];
    
    if ($count > 0) {
        echo "📋 Encontrados $count registros sin campos de auditoría\n";
        echo "🔄 Inicializando campos...\n";
        
        // Obtener fecha actual
        $now = date('Y-m-d H:i:s');
        
        // Actualizar registros existentes
        $sql = "UPDATE equipo_sonido SET 
                fecha_creacion = COALESCE(fecha_creacion, '$now'),
                fecha_ultima_edicion = COALESCE(fecha_ultima_edicion, '$now'),
                ultimo_editor = COALESCE(ultimo_editor, 'Sistema')
                WHERE fecha_creacion IS NULL OR fecha_ultima_edicion IS NULL OR ultimo_editor IS NULL";
        
        $pdo->exec($sql);
        
        echo "✅ Campos de auditoría inicializados exitosamente\n";
        
        // Verificar resultado
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM equipo_sonido WHERE fecha_creacion IS NOT NULL AND fecha_ultima_edicion IS NOT NULL");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Registros con auditoría completa: " . $result['total'] . "\n";
        
    } else {
        echo "✅ Todos los registros ya tienen campos de auditoría inicializados\n";
    }
    
    // Mostrar algunos registros como muestra
    echo "\n📋 Muestra de registros con auditoría:\n";
    $stmt = $pdo->query("SELECT idSonido, MARCA, MODELO, fecha_creacion, fecha_ultima_edicion, ultimo_editor FROM equipo_sonido LIMIT 3");
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($samples as $sample) {
        echo "- ID {$sample['idSonido']}: {$sample['MARCA']} {$sample['MODELO']}\n";
        echo "  Creado: {$sample['fecha_creacion']}\n";
        echo "  Editado: {$sample['fecha_ultima_edicion']}\n";
        echo "  Editor: {$sample['ultimo_editor']}\n\n";
    }
    
    echo "🎉 Proceso completado exitosamente!\n";
    
} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
