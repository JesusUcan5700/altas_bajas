<?php
require __DIR__ . '/vendor/autoload.php';

// Incluir la configuración de la aplicación
$config = require __DIR__ . '/frontend/config/main.php';
$app = new yii\web\Application($config);

try {
    // Obtener la conexión a la base de datos
    $db = Yii::$app->db;
    
    echo "=== Verificando estructura de tabla memoria_ram ===\n";
    
    // Obtener estructura de la tabla
    $command = $db->createCommand("DESCRIBE memoria_ram");
    $columns = $command->queryAll();
    
    echo "Columnas en la tabla memoria_ram:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    echo "\n=== Verificando datos de ejemplo ===\n";
    
    // Obtener algunos registros de ejemplo
    $command = $db->createCommand("SELECT * FROM memoria_ram LIMIT 3");
    $rams = $command->queryAll();
    
    if (empty($rams)) {
        echo "No hay registros en la tabla.\n";
    } else {
        echo "Registros de ejemplo:\n";
        foreach ($rams as $ram) {
            echo "ID: " . $ram['idRAM'] . "\n";
            echo "MARCA: " . ($ram['MARCA'] ?? 'NULL') . "\n";
            echo "CAPACIDAD: " . ($ram['CAPACIDAD'] ?? 'NULL') . "\n";
            echo "ESTADO: " . ($ram['ESTADO'] ?? 'NULL') . "\n";
            echo "---\n";
        }
    }
    
    echo "\n=== Verificando equipos dañados ===\n";
    
    // Buscar equipos con estado dañado
    $command = $db->createCommand("SELECT COUNT(*) as count FROM memoria_ram WHERE ESTADO = 'dañado(Proceso de baja)'");
    $result = $command->queryOne();
    echo "Equipos con estado dañado: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . " (línea " . $e->getLine() . ")\n";
}
