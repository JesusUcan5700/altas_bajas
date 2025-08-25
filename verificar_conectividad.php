<?php
/**
 * Script para verificar estructura de tabla conectividad
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

echo "=== ESTRUCTURA TABLA CONECTIVIDAD ===\n\n";

try {
    $sql = "DESCRIBE conectividad";
    $columns = Yii::$app->db->createCommand($sql)->queryAll();
    
    foreach ($columns as $column) {
        $pk = $column['Key'] === 'PRI' ? ' 🔑 PRIMARY KEY' : '';
        echo "- {$column['Field']} ({$column['Type']}){$pk}\n";
    }
    
    echo "\n=== VERIFICAR SI EXISTEN CAMPOS DE AUDITORÍA ===\n";
    $camposAuditoria = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    $existentes = [];
    $faltantes = [];
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], $camposAuditoria)) {
            $existentes[] = $column['Field'];
        }
    }
    
    foreach ($camposAuditoria as $campo) {
        if (!in_array($campo, $existentes)) {
            $faltantes[] = $campo;
        }
    }
    
    if (!empty($existentes)) {
        echo "✅ Campos de auditoría existentes:\n";
        foreach ($existentes as $campo) {
            echo "   - {$campo}\n";
        }
    }
    
    if (!empty($faltantes)) {
        echo "\n❌ Campos de auditoría faltantes:\n";
        foreach ($faltantes as $campo) {
            echo "   - {$campo}\n";
        }
        echo "\n🔧 Se necesita crear script de migración para agregar estos campos.\n";
    } else {
        echo "\n✅ Todos los campos de auditoría están presentes!\n";
    }
    
    echo "\n=== PROBAR ACCESO A REGISTROS ===\n";
    $conectividad = Yii::$app->db->createCommand("SELECT * FROM conectividad LIMIT 1")->queryOne();
    if ($conectividad) {
        echo "✅ Registro encontrado:\n";
        foreach ($conectividad as $campo => $valor) {
            echo "   - {$campo}: {$valor}\n";
        }
    } else {
        echo "❌ No hay registros en la tabla conectividad\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

?>
