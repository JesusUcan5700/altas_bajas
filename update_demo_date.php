<?php
// Script para actualizar fecha de creación del equipo de sonido para demostrar el tiempo activo

$pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');

echo "=== ACTUALIZACIÓN DE FECHA PARA DEMO ===\n";

// Obtener fecha actual menos algunos días para demostración
$fechaDemo = date('Y-m-d H:i:s', strtotime('-3 days -2 hours'));

echo "Actualizando fecha de creación a: $fechaDemo\n";

$sql = "UPDATE equipo_sonido SET fecha_creacion = '$fechaDemo' WHERE idSonido = 1";
$pdo->exec($sql);

echo "✅ Fecha actualizada exitosamente\n";

// Verificar el cambio
$stmt = $pdo->query('SELECT idSonido, MARCA, fecha_creacion FROM equipo_sonido WHERE idSonido = 1');
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo "\nResultado:\n";
echo "ID: " . $result['idSonido'] . "\n";
echo "Marca: " . $result['MARCA'] . "\n";
echo "Nueva fecha creación: " . $result['fecha_creacion'] . "\n";
echo "Fecha actual: " . date('Y-m-d H:i:s') . "\n";

// Calcular tiempo activo
$fechaCreacion = new DateTime($result['fecha_creacion']);
$ahora = new DateTime();
$diferencia = $ahora->diff($fechaCreacion);

echo "\nTiempo activo calculado:\n";
echo "Días: " . $diferencia->days . "\n";
echo "Horas adicionales: " . $diferencia->h . "\n";

if ($diferencia->days > 0) {
    echo "Resultado: " . $diferencia->days . ($diferencia->days == 1 ? ' día' : ' días') . "\n";
} elseif ($diferencia->h > 0) {
    echo "Resultado: " . $diferencia->h . ($diferencia->h == 1 ? ' hora' : ' horas') . "\n";
}

echo "\n🎉 Ahora el tiempo activo mostrará días en lugar de minutos!\n";
?>
