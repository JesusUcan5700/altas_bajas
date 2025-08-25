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

echo "🔄 Probando actualización de último editor...\n";

$conectividad = Conectividad::findOne(1);
if ($conectividad) {
    $conectividad->ultimo_editor = 'admin_audit_test';
    $conectividad->DESCRIPCION = 'Switch actualizado - Prueba auditoría ' . date('H:i:s');
    
    if ($conectividad->save()) {
        echo "✅ Actualización exitosa\n";
        echo "📝 Nuevo último editor: " . $conectividad->getInfoUltimoEditor() . "\n";
        echo "⏰ Tiempo última edición: " . $conectividad->getTiempoUltimaEdicion() . "\n";
        echo "📅 Tiempo activo total: " . $conectividad->getTiempoActivo() . "\n";
    } else {
        echo "❌ Error en actualización\n";
    }
}
?>
