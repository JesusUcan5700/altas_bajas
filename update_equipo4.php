<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->exec("UPDATE equipo SET ultimo_editor = 'Carlos Rodríguez', fecha_ultima_edicion = NOW() WHERE idEQUIPO = 4");
    echo "Equipo 4 actualizado correctamente\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
