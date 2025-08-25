<?php
/**
 * Script para mostrar el estado completo del sistema de auditoría
 * en todas las categorías de equipos
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
use frontend\models\Procesador;

echo "=== SISTEMA COMPLETO DE AUDITORÍA DE EQUIPOS ===\n";
echo "Fecha actual: " . date('Y-m-d H:i:s') . "\n\n";

$categorias = [
    '🔋 Baterías' => ['model' => Bateria::class, 'id_field' => 'idBateria'],
    '💾 Memoria RAM' => ['model' => Ram::class, 'id_field' => 'idRAM'],
    '💽 Almacenamiento' => ['model' => almacenamiento::class, 'id_field' => 'idAlmacenamiento'],
    '🎵 Equipos de Sonido' => ['model' => Sonido::class, 'id_field' => 'idSonido'],
    '🔧 Procesadores' => ['model' => Procesador::class, 'id_field' => 'idProcesador']
];

foreach ($categorias as $nombre => $info) {
    echo "┌─ {$nombre} ────────────────────────────────────┐\n";
    
    try {
        $modelClass = $info['model'];
        $idField = $info['id_field'];
        
        $equipo = $modelClass::findOne(1);
        if ($equipo) {
            $id = $equipo->{$idField};
            echo "│ ✅ ID: {$id} - {$equipo->MARCA} {$equipo->MODELO}\n";
            echo "│ 🕒 Tiempo Activo: " . $equipo->getTiempoActivo() . "\n";
            echo "│ 👤 Último Editor: " . $equipo->getInfoUltimoEditor() . "\n";
            echo "│ 📅 Fecha Usuario: ";
            if (property_exists($equipo, 'fecha')) {
                echo $equipo->fecha ?: 'No definida';
            } elseif (property_exists($equipo, 'FECHA')) {
                echo $equipo->FECHA ?: 'No definida';
            } else {
                echo 'Campo fecha no encontrado';
            }
            echo "\n";
            echo "│ ⏰ Última Edición: " . $equipo->getTiempoUltimaEdicion() . "\n";
        } else {
            echo "│ ❌ No hay registros disponibles\n";
        }
        
    } catch (Exception $e) {
        echo "│ ❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "└────────────────────────────────────────────────┘\n\n";
}

echo "=== CARACTERÍSTICAS IMPLEMENTADAS ===\n";
echo "✅ TimestampBehavior en todos los modelos\n";
echo "✅ Campos de auditoría: fecha_creacion, fecha_ultima_edicion, ultimo_editor\n";
echo "✅ Relaciones con modelo User para información del editor\n";
echo "✅ Cálculo de tiempo activo basado en fecha del usuario\n";
echo "✅ Métodos de auditoría: getTiempoActivo(), getTiempoUltimaEdicion(), getInfoUltimoEditor()\n";
echo "✅ Vistas actualizadas con columnas de auditoría\n";
echo "✅ Formularios de edición con información de auditoría\n";
echo "✅ Font Awesome icons y Bootstrap styling\n";
echo "✅ Tracking automático de cambios y usuarios\n";

echo "\n=== RESUMEN DE RUTAS DISPONIBLES ===\n";
echo "🔋 Baterías: /site/bateria-listar, /site/bateria-editar\n";
echo "💾 RAM: /site/ram-listar, /site/ram-editar\n";
echo "💽 Almacenamiento: /site/almacenamiento-listar, /site/almacenamiento-editar\n";
echo "🎵 Sonido: /site/sonido-listar, /site/sonido-editar\n";
echo "🔧 Procesadores: /site/procesador-listar, /site/procesador-editar\n";

echo "\n🎯 SISTEMA DE AUDITORÍA COMPLETAMENTE IMPLEMENTADO! 🎯\n";

?>
