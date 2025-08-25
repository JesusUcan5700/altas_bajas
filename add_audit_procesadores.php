<?php
/**
 * Script para agregar campos de auditoría a la tabla procesadores
 */

// Configuración de Yii
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

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

echo "=== AGREGANDO CAMPOS DE AUDITORÍA A TABLA PROCESADORES ===\n\n";

$db = Yii::$app->db;
$transaction = $db->beginTransaction();

try {
    // 1. Agregar campo fecha_creacion
    echo "📝 Agregando campo fecha_creacion...\n";
    $sql1 = "ALTER TABLE procesadores ADD COLUMN fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    $db->createCommand($sql1)->execute();
    echo "✅ Campo fecha_creacion agregado\n";

    // 2. Agregar campo fecha_ultima_edicion  
    echo "📝 Agregando campo fecha_ultima_edicion...\n";
    $sql2 = "ALTER TABLE procesadores ADD COLUMN fecha_ultima_edicion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    $db->createCommand($sql2)->execute();
    echo "✅ Campo fecha_ultima_edicion agregado\n";

    // 3. Agregar campo ultimo_editor
    echo "📝 Agregando campo ultimo_editor...\n";
    $sql3 = "ALTER TABLE procesadores ADD COLUMN ultimo_editor VARCHAR(100) DEFAULT NULL";
    $db->createCommand($sql3)->execute();
    echo "✅ Campo ultimo_editor agregado\n";

    // 4. Inicializar campos para registros existentes
    echo "📝 Inicializando campos para registros existentes...\n";
    $sql4 = "UPDATE procesadores SET 
        fecha_creacion = NOW(), 
        fecha_ultima_edicion = NOW(), 
        ultimo_editor = 'sistema' 
        WHERE fecha_creacion IS NULL";
    $affected = $db->createCommand($sql4)->execute();
    echo "✅ {$affected} registros inicializados\n";

    $transaction->commit();
    echo "\n🎉 ¡Campos de auditoría agregados exitosamente a la tabla procesadores!\n";

    // Verificar el resultado
    echo "\n=== VERIFICACIÓN FINAL ===\n";
    $columns = $db->createCommand("DESCRIBE procesadores")->queryAll();
    $camposAuditoria = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], $camposAuditoria)) {
            echo "✅ {$column['Field']} ({$column['Type']})\n";
        }
    }

} catch (Exception $e) {
    $transaction->rollBack();
    echo "❌ Error al agregar campos: " . $e->getMessage() . "\n";
    echo "🔄 Transacción revertida\n";
}

?>
