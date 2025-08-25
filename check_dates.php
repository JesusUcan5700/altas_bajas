<?php
$pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');
$stmt = $pdo->query('SELECT idSonido, MARCA, fecha_creacion, fecha_ultima_edicion FROM equipo_sonido LIMIT 1');
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo "ID: " . $result['idSonido'] . "\n";
    echo "Marca: " . $result['MARCA'] . "\n";
    echo "Fecha creación: " . $result['fecha_creacion'] . "\n";
    echo "Fecha edición: " . $result['fecha_ultima_edicion'] . "\n";
    echo "Fecha actual: " . date('Y-m-d H:i:s') . "\n";
    
    // Calcular diferencia manualmente
    $fechaCreacion = new DateTime($result['fecha_creacion']);
    $ahora = new DateTime();
    $diferencia = $ahora->diff($fechaCreacion);
    
    echo "\nCálculo de tiempo:\n";
    echo "Días: " . $diferencia->days . "\n";
    echo "Horas: " . $diferencia->h . "\n";
    echo "Minutos: " . $diferencia->i . "\n";
    echo "Segundos: " . $diferencia->s . "\n";
} else {
    echo "No hay registros";
}
?>
