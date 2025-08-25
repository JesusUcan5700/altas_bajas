<?php
/**
 * Script para estandarizar los estados en todas las categorías de equipos
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

echo "🔧 ESTANDARIZANDO ESTADOS PARA TODAS LAS CATEGORÍAS\n";
echo "==================================================\n\n";

echo "📋 Estados que se van a estandarizar:\n";
echo "   1. Activo\n";
echo "   2. Inactivo(Sin Asignar)\n";
echo "   3. dañado(Proceso de baja)\n";
echo "   4. En Mantenimiento\n\n";

// Lista de modelos que necesitan actualización
$modelos = [
    'Bateria' => 'frontend\models\Bateria',
    'ram' => 'frontend\models\ram',
    'almacenamiento' => 'frontend\models\almacenamiento', 
    'Sonido' => 'frontend\models\Sonido',
    'procesador' => 'frontend\models\procesador',
    'conectividad' => 'frontend\models\conectividad',
    'Telefonia' => 'frontend\models\Telefonia',
    'VideoVigilancia' => 'frontend\models\VideoVigilancia'
];

echo "📂 Modelos a actualizar:\n";
foreach ($modelos as $nombre => $clase) {
    echo "   ✅ $nombre\n";
}

echo "\n🚀 Procediendo con las actualizaciones...\n";
?>
