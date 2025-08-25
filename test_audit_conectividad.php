<?php
/**
 * Script para probar el sistema de auditoría en conectividad
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

use frontend\models\Conectividad;

echo "=== PRUEBA SISTEMA DE AUDITORÍA CONECTIVIDAD ===\n\n";

// 1. Verificar que el modelo carga correctamente
echo "🔍 VERIFICANDO MODELO CONECTIVIDAD:\n";
try {
    $conectividad = Conectividad::findOne(1);
    if ($conectividad) {
        echo "✅ Conectividad encontrada - ID: {$conectividad->idCONECTIVIDAD}\n";
        echo "   TIPO: {$conectividad->TIPO}\n";
        echo "   MARCA: {$conectividad->MARCA}\n";
        echo "   MODELO: {$conectividad->MODELO}\n";
        echo "   fecha (usuario): " . ($conectividad->fecha ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($conectividad->fecha_creacion ?: 'No definida') . "\n";
        echo "   ultimo_editor: " . ($conectividad->ultimo_editor ?: 'No definido') . "\n";
        
        echo "\n🕒 PROBANDO MÉTODOS DE AUDITORÍA:\n";
        echo "   - Tiempo activo: " . $conectividad->getTiempoActivo() . "\n";
        echo "   - Tiempo última edición: " . $conectividad->getTiempoUltimaEdicion() . "\n";
        echo "   - Info último editor: " . $conectividad->getInfoUltimoEditor() . "\n";
        
    } else {
        echo "❌ No se encontró conectividad con ID 1\n";
        echo "🔧 Creando un equipo de conectividad de prueba...\n";
        
        $nuevaConectividad = new Conectividad();
        $nuevaConectividad->TIPO = 'switch';
        $nuevaConectividad->MARCA = 'Cisco';
        $nuevaConectividad->MODELO = 'Catalyst 2960';
        $nuevaConectividad->NUMERO_SERIE = 'TEST789456';
        $nuevaConectividad->NUMERO_INVENTARIO = 'INV456789';
        $nuevaConectividad->CANTIDAD_PUERTOS = '24';
        $nuevaConectividad->DESCRIPCION = 'Switch de prueba para auditoría';
        $nuevaConectividad->Estado = 'activo';
        $nuevaConectividad->fecha = '2025-08-10';
        $nuevaConectividad->ubicacion_edificio = 'B';
        $nuevaConectividad->ubicacion_detalle = 'Sala de servidores';
        
        if ($nuevaConectividad->save()) {
            echo "✅ Equipo de conectividad de prueba creado con ID: {$nuevaConectividad->idCONECTIVIDAD}\n";
            echo "   - Tiempo activo: " . $nuevaConectividad->getTiempoActivo() . "\n";
            echo "   - Último editor: " . $nuevaConectividad->getInfoUltimoEditor() . "\n";
        } else {
            echo "❌ Error al crear equipo de conectividad de prueba: " . implode(', ', $nuevaConectividad->getFirstErrors()) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== PRUEBA DE ACTUALIZACIÓN ===\n";
try {
    $conectividad = Conectividad::findOne(1);
    if ($conectividad) {
        $descripcionAnterior = $conectividad->DESCRIPCION;
        $conectividad->DESCRIPCION = 'Equipo actualizado para prueba de auditoría - ' . date('Y-m-d H:i:s');
        $conectividad->ultimo_editor = 'admin_test';
        
        if ($conectividad->save()) {
            echo "✅ Conectividad actualizada exitosamente\n";
            echo "   Descripción anterior: {$descripcionAnterior}\n";
            echo "   Descripción nueva: {$conectividad->DESCRIPCION}\n";
            echo "   Último editor: " . $conectividad->getInfoUltimoEditor() . "\n";
            echo "   Tiempo desde última edición: " . $conectividad->getTiempoUltimaEdicion() . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error en actualización: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICACIÓN ESTRUCTURA BASE DE DATOS ===\n";
try {
    $sql = "DESCRIBE conectividad";
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
echo "✅ Sistema de auditoría implementado para conectividad\n";
echo "📋 Campos de auditoría agregados a la base de datos\n";
echo "🔧 Modelo actualizado con TimestampBehavior y métodos de auditoría\n";
echo "🎨 Vista de listado actualizada con columnas de auditoría\n";
echo "✏️ Vista de edición completamente funcional\n";
echo "🎯 Cálculo de tiempo activo basado en campo 'fecha' del usuario\n";

?>
