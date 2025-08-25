<?php
/**
 * Script de verificación de estados estandarizados
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

echo "🔍 VERIFICACIÓN DE ESTADOS ESTANDARIZADOS\n";
echo "=========================================\n\n";

echo "📋 Estados esperados:\n";
echo "   1. Activo\n";
echo "   2. Inactivo(Sin Asignar)\n";
echo "   3. dañado(Proceso de baja)\n";
echo "   4. En Mantenimiento\n\n";

// Lista de modelos con sus clases - TODAS LAS 13 CATEGORÍAS
$modelos = [
    'No Break' => 'frontend\models\Nobreak',
    'Cómputo' => 'frontend\models\equipo', 
    'Impresora' => 'frontend\models\impresora',
    'Cámaras (Video Vigilancia)' => 'frontend\models\VideoVigilancia',
    'Conectividad' => 'frontend\models\conectividad',
    'Telefonía' => 'frontend\models\Telefonia',
    'Procesadores' => 'frontend\models\procesador',
    'Almacenamiento' => 'frontend\models\almacenamiento',
    'Memoria RAM' => 'frontend\models\ram',
    'Equipo de Sonido' => 'frontend\models\Sonido',
    'Monitores' => 'frontend\models\monitor',
    'Baterías' => 'frontend\models\Bateria',
    'Adaptadores' => 'frontend\models\adaptador'
];

echo "🔄 Verificando estados en cada modelo:\n\n";

foreach ($modelos as $nombre => $clase) {
    echo "📂 $nombre:\n";
    
    if (class_exists($clase)) {
        if (method_exists($clase, 'getEstados')) {
            $estados = $clase::getEstados();
            $estadosVals = array_values($estados);
            
            // Verificar si tiene los 4 estados estandarizados
            $esperados = ['Activo', 'Inactivo(Sin Asignar)', 'dañado(Proceso de baja)', 'En Mantenimiento'];
            $completo = true;
            
            foreach ($esperados as $estado) {
                if (!in_array($estado, $estadosVals)) {
                    $completo = false;
                    break;
                }
            }
            
            if ($completo && count($estadosVals) == 4) {
                echo "   ✅ Estados correctos y completos\n";
            } elseif ($completo) {
                echo "   ⚠️  Estados correctos pero con extras: " . implode(', ', $estadosVals) . "\n";
            } else {
                echo "   ❌ Estados incorrectos: " . implode(', ', $estadosVals) . "\n";
            }
            
            foreach ($estadosVals as $est) {
                echo "      - $est\n";
            }
        } else {
            echo "   ❌ No tiene método getEstados()\n";
        }
    } else {
        echo "   ❌ Clase no encontrada: $clase\n";
    }
    
    echo "\n";
}

echo "✅ Verificación completada.\n";
?>
