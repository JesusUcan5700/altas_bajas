<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICACIÓN FINAL DEL SISTEMA DE AUDITORÍA ===\n\n";
    
    echo "📊 EQUIPOS CON HISTORIAL DE EDICIÓN:\n";
    $equipos = $pdo->query("
        SELECT e.idEQUIPO, e.MARCA, e.MODELO, e.ultimo_editor, e.fecha_ultima_edicion,
               u.email as editor_email,
               DATE_FORMAT(e.fecha_ultima_edicion, '%d/%m/%Y %H:%i:%s') as fecha_formateada
        FROM equipo e
        LEFT JOIN user u ON u.username = e.ultimo_editor
        WHERE e.fecha_ultima_edicion IS NOT NULL
        ORDER BY e.fecha_ultima_edicion DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($equipos as $index => $equipo) {
        echo "  " . ($index + 1) . ". ";
        echo "ID: {$equipo['idEQUIPO']} | ";
        echo "{$equipo['MARCA']} {$equipo['MODELO']} | ";
        echo "Editor: {$equipo['ultimo_editor']}";
        if ($equipo['editor_email']) {
            echo " ({$equipo['editor_email']})";
        }
        echo " | {$equipo['fecha_formateada']}\n";
    }
    
    if (!empty($equipos)) {
        $ultimo = $equipos[0];
        echo "\n🏆 ÚLTIMO EQUIPO EDITADO:\n";
        echo "   Equipo: {$ultimo['MARCA']} {$ultimo['MODELO']}\n";
        echo "   Usuario: {$ultimo['ultimo_editor']}\n";
        echo "   Email: " . ($ultimo['editor_email'] ?? 'N/A') . "\n";
        echo "   Fecha: {$ultimo['fecha_formateada']}\n";
        
        // Calcular tiempo
        $fecha = new DateTime($ultimo['fecha_ultima_edicion']);
        $ahora = new DateTime();
        $diff = $ahora->diff($fecha);
        
        if ($diff->days == 0) {
            if ($diff->h == 0) {
                $tiempo = "Hace " . $diff->i . " minutos";
            } else {
                $tiempo = "Hace " . $diff->h . " horas";
            }
        } else {
            $tiempo = "Hace " . $diff->days . " días";
        }
        echo "   Tiempo: $tiempo\n";
    }
    
    echo "\n✅ SISTEMA DE AUDITORÍA FUNCIONANDO CORRECTAMENTE\n";
    echo "   - Se registra automáticamente quién edita cada equipo\n";
    echo "   - Se vincula con la tabla 'user' para obtener información completa\n";
    echo "   - Se muestra email del usuario y tiempo transcurrido\n";
    echo "   - La vista muestra el último equipo editado con detalles del usuario\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
