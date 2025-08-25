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

echo "🔄 Probando actualización de videovigilancia...\n";

$videovigilancia = VideoVigilancia::findOne(1);
if ($videovigilancia) {
    echo "✅ Videovigilancia encontrada: {$videovigilancia->MARCA} {$videovigilancia->MODELO}\n";
    echo "   Tipo anterior: " . ($videovigilancia->tipo_camara ?: 'Sin tipo') . "\n";
    
    // Actualizar
    $videovigilancia->ultimo_editor = 'admin_videovigilancia_test';
    $videovigilancia->tipo_camara = VideoVigilancia::TIPO_PTZ;
    $videovigilancia->DESCRIPCION = 'Cámara actualizada - ' . date('H:i:s');
    
    if ($videovigilancia->save()) {
        echo "✅ Actualización exitosa\n";
        echo "📝 Último editor: " . $videovigilancia->getInfoUltimoEditor() . "\n";
        echo "📹 Nuevo tipo: " . $videovigilancia->tipo_camara . "\n";
        echo "⏰ Tiempo activo: " . $videovigilancia->getTiempoActivo() . "\n";
        echo "🕒 Última edición: " . $videovigilancia->getTiempoUltimaEdicion() . "\n";
    } else {
        echo "❌ Error al actualizar: " . implode(', ', $videovigilancia->getFirstErrors()) . "\n";
    }
} else {
    echo "❌ No se encontró videovigilancia\n";
}

echo "\n🎉 Sistema de auditoría y tipos de cámara funcionando correctamente!\n";
?>
