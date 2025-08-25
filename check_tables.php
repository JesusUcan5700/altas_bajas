<?php
require_once 'vendor/autoload.php';

// Configurar Yii
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require_once 'vendor/yiisoft/yii2/Yii.php';
require_once 'common/config/bootstrap.php';
require_once 'frontend/config/bootstrap.php';

$config = require 'frontend/config/main-local.php';
new yii\web\Application($config);

// Obtener lista de tablas
$connection = Yii::$app->db;
$tables = $connection->createCommand("SHOW TABLES")->queryColumn();

echo "=== TABLAS EN LA BASE DE DATOS ===\n";
foreach ($tables as $table) {
    echo "- $table\n";
}

// Verificar algunas tablas específicas que pueden contener equipos
$equipoTables = ['nobreak', 'equipo', 'impresora', 'monitor', 'microfono', 'bateria', 'adaptador', 'conectividad', 'telefonia', 'videovigilancia', 'sonido', 'procesador', 'almacenamiento', 'ram'];

echo "\n=== VERIFICANDO TABLAS DE EQUIPOS ===\n";
foreach ($equipoTables as $table) {
    try {
        $exists = $connection->createCommand("SHOW TABLES LIKE '$table'")->queryScalar();
        if ($exists) {
            $count = $connection->createCommand("SELECT COUNT(*) FROM $table")->queryScalar();
            echo "✅ $table: $count registros\n";
        } else {
            echo "❌ $table: No existe\n";
        }
    } catch (Exception $e) {
        echo "⚠️ $table: Error - " . $e->getMessage() . "\n";
    }
}
?>
