<?php
// Simple test script to check monitor table structure
require_once __DIR__ . '/vendor/autoload.php';

try {
    // Load Yii2 config and create application
    $config = require __DIR__ . '/frontend/config/main.php';
    $app = new yii\web\Application($config);
    
    // Test database connection
    $db = Yii::$app->db;
    $command = $db->createCommand('DESCRIBE monitor');
    $columns = $command->queryAll();
    
    echo "Monitor table structure:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    // Test if we can query monitors
    $monitors = $db->createCommand('SELECT * FROM monitor LIMIT 1')->queryAll();
    if (!empty($monitors)) {
        echo "\nSample monitor data:\n";
        foreach ($monitors[0] as $field => $value) {
            echo "- $field: $value\n";
        }
    } else {
        echo "\nNo monitors found in table.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
