<?php
// Test Monitor model attributes
require_once __DIR__ . '/vendor/autoload.php';

try {
    // Load Yii2 config and create application
    $config = require __DIR__ . '/frontend/config/main.php';
    $app = new yii\web\Application($config);
    
    // Create a Monitor model instance
    $monitor = new frontend\models\Monitor();
    
    echo "Monitor model attributes:\n";
    $attributes = $monitor->attributes();
    foreach ($attributes as $attr) {
        echo "- $attr\n";
    }
    
    echo "\nChecking specific estado attributes:\n";
    echo "hasAttribute('Estado'): " . ($monitor->hasAttribute('Estado') ? 'YES' : 'NO') . "\n";
    echo "hasAttribute('ESTADO'): " . ($monitor->hasAttribute('ESTADO') ? 'YES' : 'NO') . "\n";
    echo "hasAttribute('estado'): " . ($monitor->hasAttribute('estado') ? 'YES' : 'NO') . "\n";
    
    // Try to get table schema
    echo "\nTable schema columns:\n";
    $tableSchema = $monitor->getTableSchema();
    foreach ($tableSchema->columns as $columnName => $column) {
        echo "- $columnName ({$column->type})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
?>
