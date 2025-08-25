<?php
try {
    require 'vendor/autoload.php';
    require 'vendor/yiisoft/yii2/Yii.php';

    $config = yii\helpers\ArrayHelper::merge(
        require 'common/config/main.php',
        require 'common/config/main-local.php'
    );

    new yii\console\Application($config);

    $connection = Yii::$app->db;
    
    // Probar conexión
    echo "Probando conexión a la base de datos...\n";
    $tables = $connection->createCommand("SHOW TABLES")->queryAll();
    echo "Conexión exitosa. Tablas encontradas: " . count($tables) . "\n";
    
    // Verificar tabla baterias
    $count = $connection->createCommand("SELECT COUNT(*) FROM baterias")->queryScalar();
    echo "Número de baterías en la base de datos: " . $count . "\n";
    
    if ($count > 0) {
        // Mostrar una batería de ejemplo
        $bateria = $connection->createCommand("SELECT * FROM baterias LIMIT 1")->queryOne();
        echo "\nEjemplo de batería:\n";
        echo "ID: " . $bateria['idBateria'] . "\n";
        echo "Marca: " . $bateria['MARCA'] . "\n";
        echo "Modelo: " . $bateria['MODELO'] . "\n";
        echo "Fecha creación: " . ($bateria['fecha_creacion'] ?: 'NULL') . "\n";
        echo "Último editor: " . ($bateria['ultimo_editor'] ?: 'NULL') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
