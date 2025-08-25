<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=altas_bajas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== SIMULANDO EDICIÓN CON USUARIO REAL ===\n";
    
    // Verificar usuarios disponibles
    $users = $pdo->query("SELECT id, username, email FROM user LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    echo "Usuarios disponibles:\n";
    foreach ($users as $user) {
        echo "- ID: {$user['id']} | Username: {$user['username']} | Email: {$user['email']}\n";
    }
    
    // Usar el primer usuario para simular una edición
    if (!empty($users)) {
        $usuario = $users[0]['username'];
        echo "\n=== SIMULANDO EDICIÓN POR: $usuario ===\n";
        
        // Actualizar un equipo con este usuario
        $updateSql = "UPDATE equipo SET 
                        ultimo_editor = :usuario,
                        fecha_ultima_edicion = NOW(),
                        MARCA = CONCAT(MARCA, ' [Editado]')
                      WHERE idEQUIPO = 2";
        
        $stmt = $pdo->prepare($updateSql);
        $stmt->execute(['usuario' => $usuario]);
        
        echo "✅ Equipo 2 actualizado con usuario: $usuario\n";
        
        // Verificar el resultado
        $result = $pdo->query("
            SELECT e.idEQUIPO, e.MARCA, e.MODELO, e.ultimo_editor, e.fecha_ultima_edicion,
                   u.email as editor_email,
                   DATE_FORMAT(e.fecha_ultima_edicion, '%d/%m/%Y %H:%i') as fecha_formateada
            FROM equipo e
            LEFT JOIN user u ON u.username = e.ultimo_editor
            WHERE e.idEQUIPO = 2
        ")->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "\n📋 RESULTADO DE LA EDICIÓN:\n";
            echo "🆔 ID: " . $result['idEQUIPO'] . "\n";
            echo "🏷️  Equipo: " . $result['MARCA'] . " " . $result['MODELO'] . "\n";
            echo "👤 Editado por: " . $result['ultimo_editor'] . "\n";
            echo "📧 Email del editor: " . ($result['editor_email'] ?? 'N/A') . "\n";
            echo "📅 Fecha: " . $result['fecha_formateada'] . "\n";
        }
        
        echo "\n=== VERIFICAR ÚLTIMO EQUIPO EDITADO ===\n";
        $ultimo = $pdo->query("
            SELECT e.idEQUIPO, e.MARCA, e.MODELO, e.ultimo_editor, e.fecha_ultima_edicion,
                   u.email as editor_email,
                   DATE_FORMAT(e.fecha_ultima_edicion, '%d/%m/%Y %H:%i') as fecha_formateada
            FROM equipo e
            LEFT JOIN user u ON u.username = e.ultimo_editor
            WHERE e.fecha_ultima_edicion IS NOT NULL
            ORDER BY e.fecha_ultima_edicion DESC 
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);
        
        if ($ultimo) {
            echo "🆔 Último editado - ID: " . $ultimo['idEQUIPO'] . "\n";
            echo "🏷️  Equipo: " . $ultimo['MARCA'] . " " . $ultimo['MODELO'] . "\n";
            echo "👤 Usuario: " . $ultimo['ultimo_editor'] . " (" . ($ultimo['editor_email'] ?? 'sin email') . ")\n";
            echo "📅 Fecha: " . $ultimo['fecha_formateada'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
