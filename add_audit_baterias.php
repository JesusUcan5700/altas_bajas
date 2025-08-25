<?php
// Script para agregar campos de auditoría a baterias
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = yii\helpers\ArrayHelper::merge(
    require 'common/config/main.php',
    require 'common/config/main-local.php',
    require 'frontend/config/main.php',
    require 'frontend/config/main-local.php'
);

(new yii\web\Application($config));

try {
    $connection = Yii::$app->db;
    
    // Verificar si los campos ya existen
    $columns = $connection->createCommand("SHOW COLUMNS FROM baterias")->queryAll();
    $existingColumns = array_column($columns, 'Field');
    
    echo "Campos existentes en baterias: " . implode(', ', $existingColumns) . "\n\n";
    
    // Agregar campos si no existen
    if (!in_array('fecha_creacion', $existingColumns)) {
        echo "Agregando campo fecha_creacion...\n";
        $connection->createCommand("ALTER TABLE baterias ADD COLUMN fecha_creacion TIMESTAMP NULL")->execute();
    } else {
        echo "El campo fecha_creacion ya existe.\n";
    }
    
    if (!in_array('fecha_ultima_edicion', $existingColumns)) {
        echo "Agregando campo fecha_ultima_edicion...\n";
        $connection->createCommand("ALTER TABLE baterias ADD COLUMN fecha_ultima_edicion TIMESTAMP NULL")->execute();
    } else {
        echo "El campo fecha_ultima_edicion ya existe.\n";
    }
    
    if (!in_array('ultimo_editor', $existingColumns)) {
        echo "Agregando campo ultimo_editor...\n";
        $connection->createCommand("ALTER TABLE baterias ADD COLUMN ultimo_editor VARCHAR(100) NULL")->execute();
    } else {
        echo "El campo ultimo_editor ya existe.\n";
    }
    
    // Actualizar registros existentes
    echo "Actualizando registros existentes...\n";
    $connection->createCommand("UPDATE baterias SET fecha_creacion = NOW(), fecha_ultima_edicion = NOW(), ultimo_editor = 'Sistema' WHERE fecha_creacion IS NULL")->execute();
    
    echo "¡Campos de auditoría agregados exitosamente!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
