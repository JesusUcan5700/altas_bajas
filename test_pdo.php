<?php
// Test simple de conexión PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    echo "Conexión PDO exitosa\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM baterias");
    $count = $stmt->fetchColumn();
    echo "Número de baterías: " . $count . "\n";
    
    if ($count > 0) {
        $stmt = $pdo->query("SELECT idBateria, MARCA, MODELO, fecha_creacion, ultimo_editor FROM baterias LIMIT 1");
        $bateria = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Batería de ejemplo:\n";
        print_r($bateria);
    }
    
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
}
?>
