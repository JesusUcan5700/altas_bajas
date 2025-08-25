<?php
/**
 * Script para verificar las claves primarias de las tablas
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

echo "=== VERIFICACIÓN DE CLAVES PRIMARIAS ===\n\n";

$tablas = [
    'baterias' => 'Batería',
    'memoria_ram' => 'RAM',
    'almacenamiento' => 'Almacenamiento',
    'equipo_sonido' => 'Equipo de Sonido'
];

foreach ($tablas as $tabla => $nombre) {
    echo "📋 {$nombre} (tabla: {$tabla}):\n";
    try {
        $sql = "DESCRIBE {$tabla}";
        $columnas = Yii::$app->db->createCommand($sql)->queryAll();
        
        echo "   Columnas encontradas:\n";
        foreach ($columnas as $columna) {
            $pk = $columna['Key'] === 'PRI' ? ' 🔑 PRIMARY KEY' : '';
            echo "   - {$columna['Field']} ({$columna['Type']}){$pk}\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

echo "=== PRUEBA SIMPLE DE ACCESO A REGISTROS ===\n\n";

// Test directo con SQL
try {
    echo "🔋 Probando acceso a baterias:\n";
    $bateria = Yii::$app->db->createCommand("SELECT * FROM baterias LIMIT 1")->queryOne();
    if ($bateria) {
        echo "   ✅ Registro encontrado:\n";
        foreach ($bateria as $campo => $valor) {
            echo "   - {$campo}: {$valor}\n";
        }
    } else {
        echo "   ❌ No hay registros en baterias\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

try {
    echo "💾 Probando acceso a memoria_ram:\n";
    $ram = Yii::$app->db->createCommand("SELECT * FROM memoria_ram LIMIT 1")->queryOne();
    if ($ram) {
        echo "   ✅ Registro encontrado:\n";
        foreach ($ram as $campo => $valor) {
            echo "   - {$campo}: {$valor}\n";
        }
    } else {
        echo "   ❌ No hay registros en memoria_ram\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

?>
