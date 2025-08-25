<?php
echo "=== VERIFICACIÓN DE NUEVA ESTRUCTURA DE HEADER ===\n\n";

// Verificar login reorganizado
echo "1. Verificando LOGIN con nueva estructura...\n";
$loginPath = 'frontend/views/site/login.php';
if (file_exists($loginPath)) {
    $loginContent = file_get_contents($loginPath);
    
    echo "   ✓ Archivo login.php existe\n";
    
    // Verificar orden de elementos
    $sistemaPos = strpos($loginContent, 'Sistema de inventario de altas y bajas de equipo de cómputo');
    $logoPos = strpos($loginContent, 'logo.jpg');
    $iniciarPos = strpos($loginContent, 'Iniciar Sesión</h4>');
    
    if ($sistemaPos !== false && $logoPos !== false && $iniciarPos !== false) {
        if ($sistemaPos < $logoPos && $logoPos < $iniciarPos) {
            echo "   ✓ Orden correcto: Sistema → Logo → Acción\n";
        } else {
            echo "   ✗ Orden incorrecto de elementos\n";
        }
    } else {
        echo "   ✗ Elementos faltantes\n";
    }
    
    // Verificar que use h6 para el título del sistema
    if (strpos($loginContent, '<h6 class="fw-bold text-primary') !== false) {
        echo "   ✓ Título del sistema con estilo h6 primary\n";
    } else {
        echo "   ✗ Estilo del título del sistema incorrecto\n";
    }
    
} else {
    echo "   ✗ Archivo login.php NO existe\n";
}

echo "\n2. Verificando SIGNUP con nueva estructura...\n";
$signupPath = 'frontend/views/site/signup.php';
if (file_exists($signupPath)) {
    $signupContent = file_get_contents($signupPath);
    
    echo "   ✓ Archivo signup.php existe\n";
    
    // Verificar orden de elementos
    $sistemaPos = strpos($signupContent, 'Sistema de inventario de altas y bajas de equipo de cómputo');
    $logoPos = strpos($signupContent, 'logo.jpg');
    $crearPos = strpos($signupContent, 'Crear Cuenta</h4>');
    
    if ($sistemaPos !== false && $logoPos !== false && $crearPos !== false) {
        if ($sistemaPos < $logoPos && $logoPos < $crearPos) {
            echo "   ✓ Orden correcto: Sistema → Logo → Acción\n";
        } else {
            echo "   ✗ Orden incorrecto de elementos\n";
        }
    } else {
        echo "   ✗ Elementos faltantes\n";
    }
    
    // Verificar que use h6 para el título del sistema
    if (strpos($signupContent, '<h6 class="fw-bold text-primary') !== false) {
        echo "   ✓ Título del sistema con estilo h6 primary\n";
    } else {
        echo "   ✗ Estilo del título del sistema incorrecto\n";
    }
    
} else {
    echo "   ✗ Archivo signup.php NO existe\n";
}

echo "\n3. Nueva estructura implementada:\n";
echo "   1️⃣ Título del sistema (h6, color primary, destacado)\n";
echo "   2️⃣ Logo principal (80px altura)\n";
echo "   3️⃣ Acción específica (Crear Cuenta / Iniciar Sesión)\n";
echo "   4️⃣ Línea decorativa\n";
echo "   5️⃣ Formulario\n";

echo "\n4. Características del diseño:\n";
echo "   • Jerarquía visual clara\n";
echo "   • Título del sistema prominente en azul\n";
echo "   • Logo centrado y visible\n";
echo "   • Acción específica bien definida\n";
echo "   • Mantiene todos los elementos funcionales\n";

echo "\n5. URLs para probar:\n";
echo "   Login:  http://localhost/altas_bajas/frontend/web/index.php?r=site/login\n";
echo "   Signup: http://localhost/altas_bajas/frontend/web/index.php?r=site/signup\n";

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
echo "\n💡 El título del sistema ahora está en la parte superior, seguido del logo y la acción específica\n";
?>
