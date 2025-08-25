<?php
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

use frontend\models\Conectividad;

echo "🔍 Diagnosticando conectividad...\n";

$conectividad = Conectividad::findOne(1);
if ($conectividad) {
    echo "📋 Datos actuales:\n";
    echo "   ID: {$conectividad->idCONECTIVIDAD}\n";
    echo "   Tipo: {$conectividad->TIPO}\n";
    echo "   Último editor: " . ($conectividad->ultimo_editor ?: 'NULL') . "\n";
    
    echo "\n🔄 Intentando actualización...\n";
    $conectividad->ultimo_editor = 'admin_test';
    
    if ($conectividad->validate()) {
        echo "✅ Validación OK\n";
        if ($conectividad->save()) {
            echo "✅ Guardado exitoso\n";
            echo "📝 Último editor actualizado: " . $conectividad->getInfoUltimoEditor() . "\n";
        } else {
            echo "❌ Error al guardar: " . implode(', ', $conectividad->getFirstErrors()) . "\n";
        }
    } else {
        echo "❌ Errores de validación:\n";
        foreach ($conectividad->getErrors() as $field => $errors) {
            echo "   {$field}: " . implode(', ', $errors) . "\n";
        }
    }
} else {
    echo "❌ No se encontró conectividad con ID 1\n";
}
?>
