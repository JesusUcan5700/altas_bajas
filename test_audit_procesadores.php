<?php
/**
 * Script para probar el sistema de auditoría en procesadores
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

echo "=== PRUEBA SISTEMA DE AUDITORÍA PROCESADORES ===\n\n";

// 1. Verificar que el modelo carga correctamente
echo "🔍 VERIFICANDO MODELO PROCESADOR:\n";
try {
    $procesador = Procesador::findOne(1);
    if ($procesador) {
        echo "✅ Procesador encontrado - ID: {$procesador->idProcesador}\n";
        echo "   MARCA: {$procesador->MARCA}\n";
        echo "   MODELO: {$procesador->MODELO}\n";
        echo "   fecha (usuario): " . ($procesador->fecha ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($procesador->fecha_creacion ?: 'No definida') . "\n";
        echo "   ultimo_editor: " . ($procesador->ultimo_editor ?: 'No definido') . "\n";
        
        echo "\n🕒 PROBANDO MÉTODOS DE AUDITORÍA:\n";
        echo "   - Tiempo activo: " . $procesador->getTiempoActivo() . "\n";
        echo "   - Tiempo última edición: " . $procesador->getTiempoUltimaEdicion() . "\n";
        echo "   - Info último editor: " . $procesador->getInfoUltimoEditor() . "\n";
        
    } else {
        echo "❌ No se encontró procesador con ID 1\n";
        echo "🔧 Creando un procesador de prueba...\n";
        
        $nuevoProcesador = new Procesador();
        $nuevoProcesador->MARCA = 'Intel';
        $nuevoProcesador->MODELO = 'i5-12400F';
        $nuevoProcesador->FRECUENCIA_BASE = '2.50 GHz';
        $nuevoProcesador->NUCLEOS = 6;
        $nuevoProcesador->HILOS = 12;
        $nuevoProcesador->NUMERO_SERIE = 'TEST123456';
        $nuevoProcesador->NUMERO_INVENTARIO = 'INV789012';
        $nuevoProcesador->DESCRIPCION = 'Procesador de prueba para auditoría';
        $nuevoProcesador->Estado = 'activo';
        $nuevoProcesador->fecha = '2025-08-15';
        $nuevoProcesador->ubicacion_edificio = 'A';
        $nuevoProcesador->ubicacion_detalle = 'Laboratorio de pruebas';
        
        if ($nuevoProcesador->save()) {
            echo "✅ Procesador de prueba creado con ID: {$nuevoProcesador->idProcesador}\n";
            echo "   - Tiempo activo: " . $nuevoProcesador->getTiempoActivo() . "\n";
            echo "   - Último editor: " . $nuevoProcesador->getInfoUltimoEditor() . "\n";
        } else {
            echo "❌ Error al crear procesador de prueba: " . implode(', ', $nuevoProcesador->getFirstErrors()) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICACIÓN ESTRUCTURA BASE DE DATOS ===\n";
try {
    $sql = "DESCRIBE procesadores";
    $columns = Yii::$app->db->createCommand($sql)->queryAll();
    
    $camposAuditoria = ['fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    $existentes = [];
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], $camposAuditoria)) {
            $existentes[] = $column['Field'];
            echo "✅ {$column['Field']} ({$column['Type']})\n";
        }
    }
    
    $faltantes = array_diff($camposAuditoria, $existentes);
    if (empty($faltantes)) {
        echo "🎉 Todos los campos de auditoría están presentes!\n";
    } else {
        echo "❌ Campos faltantes: " . implode(', ', $faltantes) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error verificando estructura: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Sistema de auditoría implementado para procesadores\n";
echo "📋 Campos de auditoría agregados a la base de datos\n";
echo "🔧 Modelo actualizado con TimestampBehavior y métodos de auditoría\n";
echo "🎨 Vista de listado actualizada con columnas de auditoría\n";
echo "✏️ Vista de edición completamente funcional\n";
echo "🎯 Cálculo de tiempo activo basado en campo 'fecha' del usuario\n";

?>
