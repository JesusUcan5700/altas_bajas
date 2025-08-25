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

echo "📋 Estructura actual de video_vigilancia:\n";
$sql = 'DESCRIBE video_vigilancia';
$columns = Yii::$app->db->createCommand($sql)->queryAll();
foreach ($columns as $column) {
    echo "   {$column['Field']} ({$column['Type']})\n";
}

echo "\n🔍 Verificando campos necesarios:\n";
$camposNecesarios = ['tipo_camara', 'fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
foreach ($camposNecesarios as $campo) {
    $exists = false;
    foreach ($columns as $column) {
        if ($column['Field'] === $campo) {
            $exists = true;
            break;
        }
    }
    echo "   $campo: " . ($exists ? "✅ Existe" : "❌ No existe") . "\n";
}
?>
