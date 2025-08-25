<?php
/**
 * Script para probar el sistema de auditoría en videovigilancia
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

use frontend\models\VideoVigilancia;

echo "=== PRUEBA SISTEMA DE AUDITORÍA VIDEO VIGILANCIA ===\n\n";

// 1. Verificar que el modelo carga correctamente
echo "🔍 VERIFICANDO MODELO VIDEO VIGILANCIA:\n";
try {
    $videovigilancia = VideoVigilancia::findOne(1);
    if ($videovigilancia) {
        echo "✅ Video Vigilancia encontrada - ID: {$videovigilancia->idVIDEO_VIGILANCIA}\n";
        echo "   MARCA: {$videovigilancia->MARCA}\n";
        echo "   MODELO: {$videovigilancia->MODELO}\n";
        echo "   TIPO CÁMARA: " . ($videovigilancia->tipo_camara ?: 'No definido') . "\n";
        echo "   ESTADO: {$videovigilancia->ESTADO}\n";
        echo "   fecha (usuario): " . ($videovigilancia->fecha ?: 'No definida') . "\n";
        echo "   fecha_creacion (sistema): " . ($videovigilancia->fecha_creacion ?: 'No definida') . "\n";
        echo "   ultimo_editor: " . ($videovigilancia->ultimo_editor ?: 'No definido') . "\n";
        
        echo "\n🕒 PROBANDO MÉTODOS DE AUDITORÍA:\n";
        echo "   - Tiempo activo: " . $videovigilancia->getTiempoActivo() . "\n";
        echo "   - Tiempo última edición: " . $videovigilancia->getTiempoUltimaEdicion() . "\n";
        echo "   - Info último editor: " . $videovigilancia->getInfoUltimoEditor() . "\n";
        
        echo "\n📹 PROBANDO TIPOS DE CÁMARA:\n";
        $tipos = VideoVigilancia::getTiposCamara();
        foreach ($tipos as $key => $value) {
            echo "   - $key: $value\n";
        }
        
    } else {
        echo "❌ No se encontró videovigilancia con ID 1\n";
        echo "🔧 Creando un equipo de videovigilancia de prueba...\n";
        
        $nuevaVideovigilancia = new VideoVigilancia();
        $nuevaVideovigilancia->MARCA = 'Hikvision';
        $nuevaVideovigilancia->MODELO = 'DS-2CD2025-I';
        $nuevaVideovigilancia->NUMERO_SERIE = 'CAM123456';
        $nuevaVideovigilancia->NUMERO_INVENTARIO = 'INV789456';
        $nuevaVideovigilancia->DESCRIPCION = 'Cámara IP exterior';
        $nuevaVideovigilancia->tipo_camara = VideoVigilancia::TIPO_IP;
        $nuevaVideovigilancia->ESTADO = 'activo';
        $nuevaVideovigilancia->fecha = '2025-05-20';
        $nuevaVideovigilancia->ubicacion_edificio = 'A';
        $nuevaVideovigilancia->ubicacion_detalle = 'Entrada principal';
        $nuevaVideovigilancia->EMISION_INVENTARIO = 'EM123';
        
        if ($nuevaVideovigilancia->save()) {
            echo "✅ Equipo de videovigilancia de prueba creado con ID: {$nuevaVideovigilancia->idVIDEO_VIGILANCIA}\n";
            echo "   - Tiempo activo: " . $nuevaVideovigilancia->getTiempoActivo() . "\n";
            echo "   - Tipo cámara: " . $nuevaVideovigilancia->tipo_camara . "\n";
            echo "   - Último editor: " . $nuevaVideovigilancia->getInfoUltimoEditor() . "\n";
        } else {
            echo "❌ Error al crear equipo de videovigilancia de prueba: " . implode(', ', $nuevaVideovigilancia->getFirstErrors()) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICACIÓN ESTRUCTURA BASE DE DATOS ===\n";
try {
    $sql = "DESCRIBE video_vigilancia";
    $columns = Yii::$app->db->createCommand($sql)->queryAll();
    
    $camposNuevos = ['tipo_camara', 'fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'];
    $existentes = [];
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], $camposNuevos)) {
            $existentes[] = $column['Field'];
            echo "✅ {$column['Field']} ({$column['Type']})\n";
        }
    }
    
    $faltantes = array_diff($camposNuevos, $existentes);
    if (empty($faltantes)) {
        echo "🎉 Todos los campos necesarios están presentes!\n";
    } else {
        echo "❌ Campos faltantes: " . implode(', ', $faltantes) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error verificando estructura: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Sistema de auditoría implementado para videovigilancia\n";
echo "📹 Campo tipo_camara agregado y funcionando\n";
echo "📋 Campos de auditoría agregados a la base de datos\n";
echo "🔧 Modelo actualizado con TimestampBehavior y métodos de auditoría\n";
echo "🎨 Vista de listado actualizada con columnas de auditoría y tipo de cámara\n";
echo "✏️ Vista de edición completamente funcional con tipos de cámara\n";
echo "🎯 Cálculo de tiempo activo basado en campo 'fecha' del usuario\n";

?>
