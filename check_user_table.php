<?php
$pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
$stmt = $pdo->query('DESCRIBE user');
echo "=== Estructura de la tabla user ===\n";
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $column) {
    echo "- {$column['Field']} ({$column['Type']})\n";
}
echo "\n=== Registros de ejemplo ===\n";
$stmt = $pdo->query('SELECT id, username, email FROM user LIMIT 3');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
    echo "ID: {$user['id']} - Usuario: {$user['username']} - Email: {$user['email']}\n";
}
?>
