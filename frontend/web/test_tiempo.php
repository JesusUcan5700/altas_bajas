<?php
// Test simple con fecha manual
echo "<h2>TEST MANUAL DE CÁLCULO DE TIEMPO</h2>";

// Test 1: Fecha de hace 30 días
$fecha30dias = date('Y-m-d', strtotime('-30 days'));
echo "<h3>Test 1: Fecha de hace 30 días ($fecha30dias)</h3>";

$fechaEmision = new DateTime($fecha30dias);
$fechaActual = new DateTime();
$diferencia = $fechaActual->diff($fechaEmision);

echo "Diferencia->days: " . $diferencia->days . "<br>";
echo "Diferencia->invert: " . $diferencia->invert . "<br>";

// Cálculo manual
$timestamp1 = $fechaEmision->getTimestamp();
$timestamp2 = $fechaActual->getTimestamp();
$diasCalculados = floor(($timestamp2 - $timestamp1) / (60 * 60 * 24));
echo "Cálculo manual: " . $diasCalculados . " días<br>";

// Test 2: Fecha de hace 1 año
$fecha1ano = date('Y-m-d', strtotime('-1 year'));
echo "<h3>Test 2: Fecha de hace 1 año ($fecha1ano)</h3>";

$fechaEmision2 = new DateTime($fecha1ano);
$diferencia2 = $fechaActual->diff($fechaEmision2);

echo "Diferencia->days: " . $diferencia2->days . "<br>";
echo "Años calculados: " . round($diferencia2->days / 365.25, 2) . "<br>";

// Test 3: Actualizar un equipo con fecha real
echo "<h3>Test 3: Actualizar equipo con fecha de prueba</h3>";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
    
    // Obtener primer equipo
    $stmt = $pdo->query("SELECT idEQUIPO FROM equipo LIMIT 1");
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($equipo) {
        $idEquipo = $equipo['idEQUIPO'];
        
        // Actualizar con fecha de hace 100 días
        $fechaPrueba = date('Y-m-d', strtotime('-100 days'));
        $updateStmt = $pdo->prepare("UPDATE equipo SET EMISION_INVENTARIO = ? WHERE idEQUIPO = ?");
        $updateStmt->execute([$fechaPrueba, $idEquipo]);
        
        echo "Equipo ID $idEquipo actualizado con fecha: $fechaPrueba<br>";
        echo "<a href='http://localhost/altas_bajas/frontend/web/index.php?r=site%2Fequipo-listar' target='_blank'>Ver lista de equipos</a><br>";
        
    } else {
        echo "No se encontraron equipos en la base de datos<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
?>
