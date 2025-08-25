<?php
require_once 'frontend/web/index.php';

echo "=== PRUEBA DEL SISTEMA DE REGISTRO ===\n\n";

// Verificar que la acción signup existe
echo "1. Verificando que la acción signup existe...\n";
$controller = new \frontend\controllers\SiteController('site', Yii::$app);
if (method_exists($controller, 'actionSignup')) {
    echo "✓ La acción actionSignup existe en SiteController\n";
} else {
    echo "✗ La acción actionSignup NO existe\n";
}

// Verificar que el modelo SignupForm existe y tiene el método signup
echo "\n2. Verificando que el modelo SignupForm funciona...\n";
try {
    $signupForm = new \frontend\models\SignupForm();
    if (method_exists($signupForm, 'signup')) {
        echo "✓ El modelo SignupForm existe y tiene el método signup\n";
    } else {
        echo "✗ El modelo SignupForm NO tiene el método signup\n";
    }
} catch (Exception $e) {
    echo "✗ Error al crear el modelo SignupForm: " . $e->getMessage() . "\n";
}

// Verificar la tabla user
echo "\n3. Verificando la tabla user...\n";
try {
    $db = Yii::$app->db;
    $command = $db->createCommand("SHOW COLUMNS FROM user LIKE 'status'");
    $result = $command->queryOne();
    
    if ($result) {
        echo "✓ La columna 'status' existe en la tabla user\n";
        echo "   Tipo: " . $result['Type'] . "\n";
        echo "   Default: " . ($result['Default'] ?? 'NULL') . "\n";
    } else {
        echo "✗ La columna 'status' NO existe en la tabla user\n";
    }
} catch (Exception $e) {
    echo "✗ Error al verificar la tabla user: " . $e->getMessage() . "\n";
}

// Verificar la vista signup
echo "\n4. Verificando la vista signup...\n";
$signupViewPath = 'frontend/views/site/signup.php';
if (file_exists($signupViewPath)) {
    echo "✓ La vista signup.php existe\n";
} else {
    echo "✗ La vista signup.php NO existe\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "\nPara probar el registro:\n";
echo "1. Ir a: http://localhost/altas_bajas/frontend/web/index.php?r=site/signup\n";
echo "2. Llenar el formulario con:\n";
echo "   - Usuario: test_user\n";
echo "   - Email: test@example.com\n";
echo "   - Contraseña: password123\n";
echo "3. Verificar que el usuario se cree con status = 10\n";
?>
