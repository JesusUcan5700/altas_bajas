<?php
/**
 * Script para agregar campos de auditoría y tipo de cámara a la tabla video_vigilancia
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

echo "🔧 AGREGANDO CAMPOS A VIDEO_VIGILANCIA\n";
echo "=====================================\n\n";

try {
    $db = Yii::$app->db;
    
    // 1. Agregar campo tipo_camara
    echo "📹 Agregando campo tipo_camara...\n";
    $sql1 = "ALTER TABLE video_vigilancia ADD COLUMN tipo_camara VARCHAR(50) DEFAULT 'fija' AFTER DESCRIPCION";
    $db->createCommand($sql1)->execute();
    echo "   ✅ tipo_camara agregado exitosamente\n";
    
    // 2. Agregar campo fecha_creacion
    echo "📅 Agregando campo fecha_creacion...\n";
    $sql2 = "ALTER TABLE video_vigilancia ADD COLUMN fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    $db->createCommand($sql2)->execute();
    echo "   ✅ fecha_creacion agregado exitosamente\n";
    
    // 3. Agregar campo fecha_ultima_edicion  
    echo "📅 Agregando campo fecha_ultima_edicion...\n";
    $sql3 = "ALTER TABLE video_vigilancia ADD COLUMN fecha_ultima_edicion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    $db->createCommand($sql3)->execute();
    echo "   ✅ fecha_ultima_edicion agregado exitosamente\n";
    
    // 4. Agregar campo ultimo_editor
    echo "👤 Agregando campo ultimo_editor...\n";
    $sql4 = "ALTER TABLE video_vigilancia ADD COLUMN ultimo_editor VARCHAR(100) NULL";
    $db->createCommand($sql4)->execute();
    echo "   ✅ ultimo_editor agregado exitosamente\n";
    
    echo "\n🎉 CAMPOS AGREGADOS EXITOSAMENTE\n";
    echo "===============================\n";
    
    // Verificar la nueva estructura
    echo "\n📋 Nueva estructura de video_vigilancia:\n";
    $sql = 'DESCRIBE video_vigilancia';
    $columns = Yii::$app->db->createCommand($sql)->queryAll();
    
    $camposNuevos = ['tipo_camara', 'fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    echo "\n🔍 Campos nuevos:\n";
    foreach ($columns as $column) {
        if (in_array($column['Field'], $camposNuevos)) {
            echo "   ✅ {$column['Field']} ({$column['Type']})\n";
        }
    }
    
    echo "\n✨ ¡Listo para implementar el sistema completo!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    
    // Verificar qué campos ya existen
    try {
        $sql = 'DESCRIBE video_vigilancia';
        $columns = Yii::$app->db->createCommand($sql)->queryAll();
        $camposExistentes = array_column($columns, 'Field');
        
        echo "\n🔍 Verificando campos:\n";
        $campos = ['tipo_camara', 'fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
        foreach ($campos as $campo) {
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
