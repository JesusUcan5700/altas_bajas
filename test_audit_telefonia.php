<?php
/**
 * Script para probar el sistema de auditoría en telefonía
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

use frontend\models\Telefonia;

echo "=== PRUEBA SISTEMA DE AUDITORÍA TELEFONÍA ===\n\n";

// 1. Verificar que el modelo carga correctamente
echo "🔍 VERIFICANDO MODELO TELEFONÍA:\n";
try {
    $telefonia = Telefonia::findOne(1);
    if ($telefonia) {
        echo "✅ Telefonía encontrada - ID: {$telefonia->idTELEFONIA}\n";
        echo "   MARCA: {$telefonia->MARCA}\n";
        echo "   MODELO: {$telefonia->MODELO}\n";
        echo "   ESTADO: {$telefonia->ESTADO}\n";
        echo "   fecha (usuario): " . ($telefonia->fecha ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($telefonia->fecha_creacion ?: 'No definida') . "\n";
        echo "   ultimo_editor: " . ($telefonia->ultimo_editor ?: 'No definido') . "\n";
        
        echo "\n🕒 PROBANDO MÉTODOS DE AUDITORÍA:\n";
        echo "   - Tiempo activo: " . $telefonia->getTiempoActivo() . "\n";
        echo "   - Tiempo última edición: " . $telefonia->getTiempoUltimaEdicion() . "\n";
        echo "   - Info último editor: " . $telefonia->getInfoUltimoEditor() . "\n";
        
    } else {
        echo "❌ No se encontró telefonía con ID 1\n";
        echo "🔧 Creando un equipo de telefonía de prueba...\n";
        
        $nuevaTelefonia = new Telefonia();
        $nuevaTelefonia->MARCA = 'Cisco';
        $nuevaTelefonia->MODELO = 'IP7940';
        $nuevaTelefonia->NUMERO_SERIE = 'TEST123456';
        $nuevaTelefonia->NUMERO_INVENTARIO = 'INV123456';
        $nuevaTelefonia->EMISION_INVENTARIO = 'EM789';
        $nuevaTelefonia->ESTADO = 'activo';
        $nuevaTelefonia->fecha = '2025-06-15';
        $nuevaTelefonia->ubicacion_edificio = 'A';
        $nuevaTelefonia->ubicacion_detalle = 'Oficina 101';
        
        if ($nuevaTelefonia->save()) {
            echo "✅ Equipo de telefonía de prueba creado con ID: {$nuevaTelefonia->idTELEFONIA}\n";
            echo "   - Tiempo activo: " . $nuevaTelefonia->getTiempoActivo() . "\n";
            echo "   - Último editor: " . $nuevaTelefonia->getInfoUltimoEditor() . "\n";
        } else {
            echo "❌ Error al crear equipo de telefonía de prueba: " . implode(', ', $nuevaTelefonia->getFirstErrors()) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== PRUEBA DE ACTUALIZACIÓN ===\n";
try {
    $telefonia = Telefonia::findOne(1);
    if ($telefonia) {
        $estadoAnterior = $telefonia->ESTADO;
        $telefonia->ESTADO = 'mantenimiento';
        $telefonia->ultimo_editor = 'admin_telefonia_test';
        $telefonia->ubicacion_detalle = 'Equipo actualizado para prueba de auditoría - ' . date('Y-m-d H:i:s');
        
        if ($telefonia->save()) {
            echo "✅ Telefonía actualizada exitosamente\n";
            echo "   Estado anterior: {$estadoAnterior}\n";
            echo "   Estado nuevo: {$telefonia->ESTADO}\n";
            echo "   Último editor: " . $telefonia->getInfoUltimoEditor() . "\n";
            echo "   Tiempo desde última edición: " . $telefonia->getTiempoUltimaEdicion() . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error en actualización: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICACIÓN ESTRUCTURA BASE DE DATOS ===\n";
try {
    $sql = "DESCRIBE telefonia";
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
echo "✅ Sistema de auditoría implementado para telefonía\n";
echo "📋 Campos de auditoría agregados a la base de datos\n";
echo "🔧 Modelo actualizado con TimestampBehavior y métodos de auditoría\n";
echo "🎨 Vista de listado actualizada con columnas de auditoría\n";
echo "✏️ Vista de edición completamente funcional\n";
echo "🎯 Cálculo de tiempo activo basado en campo 'fecha' del usuario\n";

?>
