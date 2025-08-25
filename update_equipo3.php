<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("UPDATE equipo SET ultimo_editor = 'cris', fecha_ultima_edicion = NOW() WHERE idEQUIPO = 3");
    echo "Equipo 3 actualizado con usuario cris";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
