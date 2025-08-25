<?php
$pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
echo "Verificando tabla final:\n";
$stmt = $pdo->query('DESCRIBE equipo_sonido');
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo $col['Field'] . ' - ' . $col['Type'] . "\n";
}
?>
