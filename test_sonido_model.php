<?php
// Script para probar el modelo Sonido después de agregar campos de auditoría

require_once __DIR__ . '/vendor/autoload.php';

// Configurar la aplicación básica de Yii
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/frontend/config/main.php'
);

$application = new yii\web\Application($config);

echo "=== PRUEBA DEL MODELO SONIDO ===\n";

try {
    // Probar la conexión del modelo
    $model = new frontend\models\Sonido();
    echo "✅ Modelo Sonido creado exitosamente\n";
    
    // Verificar que los campos de auditoría estén disponibles
    $attributes = $model->attributes();
    echo "\n📋 Atributos del modelo:\n";
    foreach ($attributes as $attr) {
        echo "- $attr\n";
    }
    
    // Verificar específicamente los campos de auditoría
    $audit_fields = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    $missing_in_model = array_diff($audit_fields, $attributes);
    
    if (empty($missing_in_model)) {
        echo "\n✅ Todos los campos de auditoría están disponibles en el modelo\n";
    } else {
        echo "\n❌ Campos faltantes en el modelo: " . implode(', ', $missing_in_model) . "\n";
    }
    
    // Probar que los métodos de auditoría funcionen
    echo "\n🧪 Probando métodos de auditoría:\n";
    try {
        echo "- getTiempoActivo(): " . $model->getTiempoActivo() . "\n";
        echo "- getTiempoUltimaEdicion(): " . $model->getTiempoUltimaEdicion() . "\n";
        echo "- getInfoUltimoEditor(): " . $model->getInfoUltimoEditor() . "\n";
        echo "✅ Métodos de auditoría funcionan correctamente\n";
    } catch (Exception $e) {
        echo "❌ Error en métodos de auditoría: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
