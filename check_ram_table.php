<?php
// Script simple para verificar estructura de memoria_ram
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "altas_bajas";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ESTRUCTURA DE TABLA memoria_ram ===\n\n";
    
    $stmt = $pdo->query("DESCRIBE memoria_ram");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . " - " . $column['Key'] . "\n";
    }
    
    echo "\n=== EJEMPLO DE REGISTRO ===\n\n";
    
    $stmt = $pdo->query("SELECT * FROM memoria_ram LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        foreach ($row as $field => $value) {
            echo "$field: $value\n";
        }
    } else {
        echo "No hay registros en la tabla.\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
