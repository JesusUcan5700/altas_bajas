<?php
// Verificar estructura real de la tabla monitor
try {
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ESTRUCTURA TABLA MONITOR ===\n";
    $stmt = $pdo->query("DESCRIBE monitor");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo "Columna: {$col['Field']} | Tipo: {$col['Type']} | NULL: {$col['Null']} | Key: {$col['Key']}\n";
    }
    
    echo "\n=== MUESTRA DE DATOS ===\n";
    $stmt = $pdo->query("SELECT * FROM monitor LIMIT 1");
    $sample = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($sample) {
        foreach ($sample as $field => $value) {
            echo "$field: $value\n";
        }
    } else {
        echo "No hay datos en la tabla\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
