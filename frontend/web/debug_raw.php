<?php
// Test directo de la base de datos
$connection = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
$stmt = $connection->query("SELECT idEQUIPO, EMISION_INVENTARIO FROM equipo LIMIT 5");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>DATOS RAW DE LA BASE DE DATOS</h2>";
foreach ($results as $row) {
    echo "<div style='border: 1px solid #333; margin: 5px; padding: 10px;'>";
    echo "ID: " . $row['idEQUIPO'] . "<br>";
    echo "EMISION_INVENTARIO: '" . $row['EMISION_INVENTARIO'] . "'<br>";
    echo "Length: " . strlen($row['EMISION_INVENTARIO']) . "<br>";
    echo "Is null: " . (is_null($row['EMISION_INVENTARIO']) ? 'YES' : 'NO') . "<br>";
    echo "Is empty: " . (empty($row['EMISION_INVENTARIO']) ? 'YES' : 'NO') . "<br>";
    
    if (!empty($row['EMISION_INVENTARIO'])) {
        $today = new DateTime();
        $emision = new DateTime($row['EMISION_INVENTARIO']);
        $diff = $today->diff($emision);
        echo "Cálculo manual: " . $diff->days . " días<br>";
    }
    echo "</div>";
}
?>
