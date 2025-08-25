<?php
// Direct database test for monitor table
try {
    $host = 'localhost';
    $dbname = 'altas_bajas';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if monitor table exists and get its structure
    echo "Checking monitor table structure...\n";
    $stmt = $pdo->query("SHOW CREATE TABLE monitor");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "CREATE TABLE statement:\n";
    echo $result['Create Table'] . "\n\n";
    
    // Get column details
    echo "Column details:\n";
    $stmt = $pdo->query("DESCRIBE monitor");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['Field']} ({$row['Type']}) " . 
             ($row['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . 
             ($row['Key'] ? " KEY: {$row['Key']}" : '') . 
             ($row['Default'] !== null ? " DEFAULT: {$row['Default']}" : '') . "\n";
    }
    
} catch (PDOException $e) {
    echo 'Database Error: ' . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
