<?php
/**
 * Script para agregar campos de auditoría a la tabla telefonia
 */

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/common/config/bootstrap.php');
require(__DIR__ . '/frontend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/common/config/main.php'),
    require(__DIR__ . '/common/config/main-local.php'),
    require(__DIR__ . '/frontend/config/main.php'),
    require(__DIR__ . '/frontend/config/main-local.php')
);

$application = new yii\web\Application($config);

echo "🔧 AGREGANDO CAMPOS DE AUDITORÍA A TELEFONIA\n";
echo "==========================================\n\n";

try {
    $db = Yii::$app->db;
    
    // 1. Agregar campo fecha_creacion
    echo "📅 Agregando campo fecha_creacion...\n";
    $sql1 = "ALTER TABLE telefonia ADD COLUMN fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    $db->createCommand($sql1)->execute();
    echo "   ✅ fecha_creacion agregado exitosamente\n";
    
    // 2. Agregar campo fecha_ultima_edicion  
    echo "📅 Agregando campo fecha_ultima_edicion...\n";
    $sql2 = "ALTER TABLE telefonia ADD COLUMN fecha_ultima_edicion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    $db->createCommand($sql2)->execute();
    echo "   ✅ fecha_ultima_edicion agregado exitosamente\n";
    
    // 3. Agregar campo ultimo_editor
    echo "👤 Agregando campo ultimo_editor...\n";
    $sql3 = "ALTER TABLE telefonia ADD COLUMN ultimo_editor VARCHAR(100) NULL";
    $db->createCommand($sql3)->execute();
    echo "   ✅ ultimo_editor agregado exitosamente\n";
    
    echo "\n🎉 CAMPOS DE AUDITORÍA AGREGADOS EXITOSAMENTE\n";
    echo "============================================\n";
    
    // Verificar la nueva estructura
    echo "\n📋 Nueva estructura de telefonia:\n";
    $sql = 'DESCRIBE telefonia';
    $columns = Yii::$app->db->createCommand($sql)->queryAll();
    
    $camposAuditoria = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    echo "\n🔍 Campos de auditoría:\n";
    foreach ($columns as $column) {
        if (in_array($column['Field'], $camposAuditoria)) {
            echo "   ✅ {$column['Field']} ({$column['Type']})\n";
        }
    }
    
    echo "\n✨ ¡Listo para implementar el sistema de auditoría en telefonía!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    
    // Verificar qué campos ya existen
    try {
        $sql = 'DESCRIBE telefonia';
        $columns = Yii::$app->db->createCommand($sql)->queryAll();
        $camposExistentes = array_column($columns, 'Field');
        
        echo "\n🔍 Campos existentes:\n";
        $camposAuditoria = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
        foreach ($camposAuditoria as $campo) {
            if (in_array($campo, $camposExistentes)) {
                echo "   ✅ $campo ya existe\n";
            } else {
                echo "   ❌ $campo falta\n";
            }
        }
        
    } catch (Exception $e2) {
        echo "❌ Error verificando estructura: " . $e2->getMessage() . "\n";
    }
}
?>
