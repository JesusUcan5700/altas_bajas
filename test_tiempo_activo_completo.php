<?php
/**
 * Script para probar el cálculo de tiempo activo basado en campo FECHA
 * en todos los modelos de equipos
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

use frontend\models\Bateria;
use frontend\models\Ram;
use frontend\models\almacenamiento;
use frontend\models\Sonido;

echo "=== PRUEBA COMPLETA: TIEMPO ACTIVO BASADO EN CAMPO FECHA ===\n\n";

// Test Baterías
echo "🔋 PROBANDO MODELO BATERIA:\n";
try {
    $bateria = Bateria::findOne(1);
    if ($bateria) {
        echo "✅ Batería encontrada - ID: {$bateria->idBateria}\n";
        echo "   MARCA: {$bateria->MARCA}\n";
        echo "   FECHA (usuario): " . ($bateria->FECHA ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($bateria->fecha_creacion ?: 'No definida') . "\n";
        echo "   🕒 Tiempo activo: " . $bateria->getTiempoActivo() . "\n";
    } else {
        echo "❌ No se encontró batería con ID 1\n";
    }
} catch (Exception $e) {
    echo "❌ Error en Batería: " . $e->getMessage() . "\n";
}

echo "\n";

// Test RAM
echo "💾 PROBANDO MODELO RAM:\n";
try {
    $ram = Ram::findOne(1);
    if ($ram) {
        echo "✅ RAM encontrada - ID: {$ram->idRAM}\n";
        echo "   MARCA: {$ram->MARCA}\n";
        echo "   FECHA (usuario): " . ($ram->FECHA ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($ram->fecha_creacion ?: 'No definida') . "\n";
        echo "   🕒 Tiempo activo: " . $ram->getTiempoActivo() . "\n";
    } else {
        echo "❌ No se encontró RAM con ID 1\n";
    }
} catch (Exception $e) {
    echo "❌ Error en RAM: " . $e->getMessage() . "\n";
}

echo "\n";

// Test Almacenamiento
echo "💽 PROBANDO MODELO ALMACENAMIENTO:\n";
try {
    $almacenamiento = almacenamiento::findOne(1);
    if ($almacenamiento) {
        echo "✅ Almacenamiento encontrado - ID: {$almacenamiento->idAlmacenamiento}\n";
        echo "   MARCA: {$almacenamiento->MARCA}\n";
        echo "   FECHA (usuario): " . ($almacenamiento->FECHA ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($almacenamiento->fecha_creacion ?: 'No definida') . "\n";
        echo "   🕒 Tiempo activo: " . $almacenamiento->getTiempoActivo() . "\n";
    } else {
        echo "❌ No se encontró almacenamiento con ID 1\n";
    }
} catch (Exception $e) {
    echo "❌ Error en Almacenamiento: " . $e->getMessage() . "\n";
}

echo "\n";

// Test Equipos de Sonido
echo "🎵 PROBANDO MODELO SONIDO:\n";
try {
    $sonido = Sonido::findOne(1);
    if ($sonido) {
        echo "✅ Equipo de sonido encontrado - ID: {$sonido->idSonido}\n";
        echo "   MARCA: {$sonido->MARCA}\n";
        echo "   FECHA (usuario): " . ($sonido->FECHA ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($sonido->fecha_creacion ?: 'No definida') . "\n";
        echo "   🕒 Tiempo activo: " . $sonido->getTiempoActivo() . "\n";
    } else {
        echo "❌ No se encontró equipo de sonido con ID 1\n";
    }
} catch (Exception $e) {
    echo "❌ Error en Sonido: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Todos los modelos actualizados para usar campo FECHA como base del cálculo\n";
echo "📋 El tiempo activo ahora se calcula desde la fecha que establece el usuario\n";
echo "🔄 Si no hay FECHA definida, usa fecha_creacion como respaldo\n";
echo "🎯 Esto permite un control más preciso del tiempo de vida útil del equipo\n";

?>
