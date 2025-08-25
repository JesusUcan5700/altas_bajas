<?php
/**
 * Script para actualizar la tabla adaptadores con campos de auditoría
 * Ejecutar desde la raíz del proyecto con: php update_adaptador_auditoria.php
 */

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'altas_bajas';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado a la base de datos.\n";
    
    // 1. Verificar estructura actual de la tabla
    echo "\n=== Verificando estructura actual de la tabla adaptadores ===\n";
    $stmt = $pdo->query("DESCRIBE adaptadores");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $existingColumns = [];
    foreach ($columns as $column) {
        $existingColumns[] = $column['Field'];
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // 2. Añadir nueva columna EMISION_INVENTARIO y eliminar FECHA
    if (in_array('FECHA', $existingColumns)) {
        echo "\n=== Actualizando campos de fecha ===\n";
        
        // Primero añadir la nueva columna
        $pdo->exec("ALTER TABLE adaptadores ADD COLUMN EMISION_INVENTARIO DATE");
        echo "✓ Columna EMISION_INVENTARIO añadida\n";
        
        // Migrar datos de FECHA a EMISION_INVENTARIO
        $pdo->exec("UPDATE adaptadores SET EMISION_INVENTARIO = FECHA WHERE FECHA IS NOT NULL");
        echo "✓ Datos migrados de FECHA a EMISION_INVENTARIO\n";
        
        // Eliminar la columna FECHA
        $pdo->exec("ALTER TABLE adaptadores DROP COLUMN FECHA");
        echo "✓ Columna FECHA eliminada\n";
    } else {
        echo "La columna 'FECHA' no existe, añadiendo EMISION_INVENTARIO directamente\n";
        if (!in_array('EMISION_INVENTARIO', $existingColumns)) {
            $pdo->exec("ALTER TABLE adaptadores ADD COLUMN EMISION_INVENTARIO DATE");
            echo "✓ Columna EMISION_INVENTARIO añadida\n";
        }
    }
    
    // 3. Añadir campos de auditoría
    echo "\n=== Añadiendo campos de auditoría ===\n";
    
    if (!in_array('fecha_creacion', $existingColumns)) {
        $pdo->exec("ALTER TABLE adaptadores ADD COLUMN fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP");
        echo "✓ Campo fecha_creacion añadido\n";
    }
    
    if (!in_array('fecha_ultima_edicion', $existingColumns)) {
        $pdo->exec("ALTER TABLE adaptadores ADD COLUMN fecha_ultima_edicion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        echo "✓ Campo fecha_ultima_edicion añadido\n";
    }
    
    if (!in_array('ultimo_editor', $existingColumns)) {
        $pdo->exec("ALTER TABLE adaptadores ADD COLUMN ultimo_editor VARCHAR(100)");
        echo "✓ Campo ultimo_editor añadido\n";
    }
    
    // 4. Actualizar registros existentes
    echo "\n=== Actualizando registros existentes ===\n";
    
    // Contar registros
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM adaptadores");
    $total = $stmt->fetch()['total'];
    echo "Total de adaptadores encontrados: $total\n";
    
    if ($total > 0) {
        // Establecer fecha_creacion para registros sin ella
        $pdo->exec("UPDATE adaptadores SET fecha_creacion = NOW() WHERE fecha_creacion IS NULL");
        
        // Establecer fecha_ultima_edicion para registros sin ella
        $pdo->exec("UPDATE adaptadores SET fecha_ultima_edicion = NOW() WHERE fecha_ultima_edicion IS NULL");
        
        // Establecer ultimo_editor como 'Sistema' para registros sin editor
        $pdo->exec("UPDATE adaptadores SET ultimo_editor = 'Sistema' WHERE ultimo_editor IS NULL OR ultimo_editor = ''");
        
        echo "✓ Registros existentes actualizados\n";
    }
    
    // 5. Verificar estructura final
    echo "\n=== Estructura final de la tabla adaptadores ===\n";
    $stmt = $pdo->query("DESCRIBE adaptadores");
    $finalColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($finalColumns as $column) {
        echo "- {$column['Field']} ({$column['Type']}) " . 
             ($column['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . 
             ($column['Default'] ? " DEFAULT {$column['Default']}" : '') . "\n";
    }
    
    // 6. Mostrar algunos registros de ejemplo
    echo "\n=== Registros de ejemplo ===\n";
    $stmt = $pdo->query("SELECT idAdaptador, MARCA, MODELO, EMISION_INVENTARIO, fecha_creacion, ultimo_editor FROM adaptadores LIMIT 3");
    $examples = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($examples as $example) {
        echo "ID: {$example['idAdaptador']} - {$example['MARCA']} {$example['MODELO']} - ";
        echo "Emisión: {$example['EMISION_INVENTARIO']} - Editor: {$example['ultimo_editor']}\n";
    }
    
    echo "\n✅ ¡Migración de auditoría para adaptadores completada exitosamente!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
