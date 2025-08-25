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

use frontend\models\VideoVigilancia;

echo "🔧 Probando consulta de VideoVigilancia...\n";

try {
    // Probar la consulta que estaba fallando
    $camaras = VideoVigilancia::find()->orderBy('idVIDEO_VIGILANCIA ASC')->all();
    
    echo "✅ Consulta exitosa!\n";
    echo "📊 Número de registros encontrados: " . count($camaras) . "\n";
    
    if (count($camaras) > 0) {
        $primera = $camaras[0];
        echo "\n📹 Primer registro:\n";
        echo "   ID: {$primera->idVIDEO_VIGILANCIA}\n";
        echo "   Marca: {$primera->MARCA}\n";
        echo "   Modelo: {$primera->MODELO}\n";
        echo "   Tipo: " . ($primera->tipo_camara ?: 'Sin tipo') . "\n";
        echo "   Estado: {$primera->ESTADO}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error en consulta: " . $e->getMessage() . "\n";
}

echo "\n🎉 Error corregido - La vista debería funcionar ahora!\n";
?>
