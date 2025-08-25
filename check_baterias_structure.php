<?php
// Script simple para verificar estructura de tabla baterias
$config = require 'frontend/config/main-local.php';

// Configuración de base de datos
$host = $config['components']['db']['dsn'];
$username = $config['components']['db']['username'];
$password = $config['components']['db']['password'];

// Extraer database name del DSN
preg_match('/dbname=([^;]+)/', $host, $matches);
$dbname = $matches[1];
preg_match('/host=([^;]+)/', $host, $matches);
$hostname = $matches[1];

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("DESCRIBE baterias");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Estructura de la tabla 'baterias':\n";
    echo str_pad("Campo", 25) . str_pad("Tipo", 25) . str_pad("Null", 8) . str_pad("Key", 8) . "Default\n";
    echo str_repeat("-", 90) . "\n";
    
    foreach ($columns as $column) {
        echo str_pad($column['Field'], 25) . 
             str_pad($column['Type'], 25) . 
             str_pad($column['Null'], 8) . 
             str_pad($column['Key'], 8) . 
             ($column['Default'] ?? 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
