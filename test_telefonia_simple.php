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

use frontend\models\Telefonia;

echo "🔄 Probando sistema de auditoría telefonía...\n";

$telefonia = Telefonia::findOne(1);
if ($telefonia) {
    echo "✅ Telefonía encontrada: {$telefonia->MARCA} {$telefonia->MODELO}\n";
    
    // Actualizar
    $telefonia->ultimo_editor = 'admin_test_telefonia';
    if ($telefonia->save()) {
        echo "✅ Actualización exitosa\n";
        echo "📝 Último editor: " . $telefonia->getInfoUltimoEditor() . "\n";
        echo "⏰ Tiempo activo: " . $telefonia->getTiempoActivo() . "\n";
    } else {
        echo "❌ Error al actualizar\n";
    }
} else {
    echo "❌ No se encontró telefonía\n";
}

// Verificar estructura
echo "\n📋 Verificando estructura:\n";
$sql = 'DESCRIBE telefonia';
$columns = Yii::$app->db->createCommand($sql)->queryAll();
$campos = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
foreach ($campos as $campo) {
    $existe = false;
    foreach ($columns as $col) {
        if ($col['Field'] === $campo) {
            $existe = true;
            break;
        }
    }
    echo ($existe ? "✅" : "❌") . " $campo\n";
}

echo "\n🎉 Sistema de auditoría para telefonía implementado!\n";
?>
