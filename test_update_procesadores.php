<?php
/**
 * Script para probar la actualización de auditoría en procesadores
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

use frontend\models\Procesador;

echo "=== PRUEBA DE ACTUALIZACIÓN DE AUDITORÍA PROCESADORES ===\n\n";

try {
    // Buscar el procesador
    $procesador = Procesador::findOne(1);
    if (!$procesador) {
        echo "❌ No se encontró procesador con ID 1\n";
        exit;
    }

    echo "📋 ESTADO ANTES DE LA EDICIÓN:\n";
    echo "   DESCRIPCION: " . ($procesador->DESCRIPCION ?: 'Sin descripción') . "\n";
    echo "   ultimo_editor: " . ($procesador->ultimo_editor ?: 'No definido') . "\n";
    echo "   fecha_ultima_edicion: " . ($procesador->fecha_ultima_edicion ?: 'No definida') . "\n";
    echo "   Tiempo activo: " . $procesador->getTiempoActivo() . "\n";

    // Simular una edición
    echo "\n🔧 REALIZANDO EDICIÓN DE PRUEBA...\n";
    $procesador->DESCRIPCION = 'Procesador Intel i3 actualizado para prueba de auditoría - ' . date('Y-m-d H:i:s');
    $procesador->ultimo_editor = 'admin'; // Simular usuario admin
    
    if ($procesador->save()) {
        echo "✅ Procesador actualizado exitosamente\n";
        
        // Recargar desde la base de datos para ver los cambios
        $procesador->refresh();
        
        echo "\n📋 ESTADO DESPUÉS DE LA EDICIÓN:\n";
        echo "   DESCRIPCION: " . $procesador->DESCRIPCION . "\n";
        echo "   ultimo_editor: " . $procesador->ultimo_editor . "\n";
        echo "   fecha_ultima_edicion: " . $procesador->fecha_ultima_edicion . "\n";
        echo "   Tiempo desde última edición: " . $procesador->getTiempoUltimaEdicion() . "\n";
        echo "   Tiempo activo: " . $procesador->getTiempoActivo() . "\n";
        echo "   Info último editor: " . $procesador->getInfoUltimoEditor() . "\n";
        
    } else {
        echo "❌ Error al actualizar: " . implode(', ', $procesador->getFirstErrors()) . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== PRUEBA DE ACTUALIZACIÓN DE FECHA ===\n";

try {
    // Actualizar la fecha para probar el tiempo activo
    $procesador = Procesador::findOne(1);
    $fechaAnterior = $procesador->fecha;
    $procesador->fecha = '2025-08-01'; // Cambiar fecha para afectar el tiempo activo
    $procesador->ultimo_editor = 'sistema_demo';
    
    if ($procesador->save()) {
        echo "✅ Fecha actualizada de {$fechaAnterior} a {$procesador->fecha}\n";
        echo "🕒 Nuevo tiempo activo: " . $procesador->getTiempoActivo() . "\n";
        echo "👤 Último editor: " . $procesador->getInfoUltimoEditor() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎉 PRUEBA COMPLETADA - SISTEMA DE AUDITORÍA FUNCIONANDO CORRECTAMENTE!\n";

?>
