<?php
// Prueba directa en base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== PROBANDO SISTEMA DE AUDITORÍA ===\n";
    
    // Verificar que los campos existen
    $stmt = $pdo->query("DESCRIBE equipo");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasAuditFields = false;
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['fecha_ultima_edicion', 'ultimo_editor'])) {
            echo "✅ Campo de auditoría encontrado: " . $column['Field'] . "\n";
            $hasAuditFields = true;
        }
    }
    
    if (!$hasAuditFields) {
        echo "❌ No se encontraron campos de auditoría\n";
        exit;
    }
    
    echo "\n=== ACTUALIZAR UN EQUIPO ===\n";
    
    // Actualizar un equipo manualmente
    $updateSql = "UPDATE equipo SET 
                    ultimo_editor = 'María García',
                    fecha_ultima_edicion = NOW()
                  WHERE idEQUIPO = 3";
    
    $result = $pdo->exec($updateSql);
    echo "✅ Equipo actualizado (filas afectadas: $result)\n";
    
    echo "\n=== VERIFICAR ÚLTIMO EQUIPO EDITADO ===\n";
    
    // Obtener el último equipo editado
    $stmt = $pdo->query("
        SELECT idEQUIPO, MARCA, MODELO, ultimo_editor, fecha_ultima_edicion,
               DATE_FORMAT(fecha_ultima_edicion, '%d/%m/%Y %H:%i') as fecha_formateada
        FROM equipo 
        WHERE fecha_ultima_edicion IS NOT NULL
        ORDER BY fecha_ultima_edicion DESC 
        LIMIT 1
    ");
    
    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ultimo) {
        echo "🆔 ID: " . $ultimo['idEQUIPO'] . "\n";
        echo "🏷️  Equipo: " . $ultimo['MARCA'] . " " . $ultimo['MODELO'] . "\n";
        echo "👤 Último editor: " . $ultimo['ultimo_editor'] . "\n";
        echo "📅 Fecha edición: " . $ultimo['fecha_formateada'] . "\n";
        
        // Calcular tiempo transcurrido
        $fechaEdicion = new DateTime($ultimo['fecha_ultima_edicion']);
        $ahora = new DateTime();
        $diff = $ahora->diff($fechaEdicion);
        
        if ($diff->days == 0) {
            if ($diff->h == 0) {
                $tiempo = "Hace " . $diff->i . " minutos";
            } else {
                $tiempo = "Hace " . $diff->h . " horas";
            }
        } else {
            $tiempo = "Hace " . $diff->days . " días";
        }
        
        echo "⏰ Tiempo transcurrido: $tiempo\n";
    } else {
        echo "❌ No se encontraron equipos con historial de edición\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
