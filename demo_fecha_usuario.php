<?php
// Script para demostrar el cálculo de tiempo activo basado en el campo FECHA

$pdo = new PDO('mysql:host=localhost;dbname=altas_bajas', 'root', '');

echo "=== DEMO: TIEMPO ACTIVO BASADO EN CAMPO FECHA ===\n";

// Establecer una fecha específica en el campo FECHA (por ejemplo, hace 7 días)
$fechaDemo = date('Y-m-d', strtotime('-7 days'));

echo "Estableciendo FECHA del equipo a: $fechaDemo\n";

$sql = "UPDATE equipo_sonido SET FECHA = '$fechaDemo' WHERE idSonido = 1";
$pdo->exec($sql);

echo "✅ Campo FECHA actualizado\n";

// Verificar el resultado
$stmt = $pdo->query('SELECT idSonido, MARCA, FECHA, fecha_creacion FROM equipo_sonido WHERE idSonido = 1');
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo "\n📋 Estado actual del registro:\n";
echo "ID: " . $result['idSonido'] . "\n";
echo "Marca: " . $result['MARCA'] . "\n";
echo "Campo FECHA (usuario): " . $result['FECHA'] . "\n";
echo "Campo fecha_creacion (sistema): " . $result['fecha_creacion'] . "\n";
echo "Fecha actual: " . date('Y-m-d') . "\n";

// Calcular tiempo activo basado en FECHA
$fechaInicio = new DateTime($result['FECHA']);
$ahora = new DateTime();
$diferencia = $ahora->diff($fechaInicio);

echo "\n⏰ Cálculo de tiempo activo:\n";
echo "Diferencia en días: " . $diferencia->days . "\n";

if ($diferencia->days > 0) {
    $tiempo = $diferencia->days . ($diferencia->days == 1 ? ' día' : ' días');
} elseif ($diferencia->h > 0) {
    $tiempo = $diferencia->h . ($diferencia->h == 1 ? ' hora' : ' horas');
} else {
    $tiempo = 'Menos de 1 día';
}

echo "Tiempo activo mostrado: $tiempo\n";

echo "\n🎯 Ahora el tiempo activo se calcula desde la fecha que establece el usuario en el formulario!\n";
echo "💡 Puedes cambiar la FECHA en el formulario de edición para ver diferentes tiempos activos.\n";
?>
