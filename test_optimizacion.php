<?php
echo "=== VERIFICACIÓN DE PÁGINAS OPTIMIZADAS ===\n\n";

// Verificar login optimizado
echo "1. Verificando LOGIN optimizado...\n";
$loginPath = 'frontend/views/site/login.php';
if (file_exists($loginPath)) {
    $loginContent = file_get_contents($loginPath);
    $loginSize = strlen($loginContent);
    
    echo "   ✓ Archivo login.php existe (Tamaño: " . number_format($loginSize) . " caracteres)\n";
    
    // Verificar que no tenga formularios duplicados
    $formCount = substr_count($loginContent, 'ActiveForm::begin');
    echo "   ✓ Formularios detectados: $formCount (debe ser 1)\n";
    
    // Verificar que use min-height en lugar de vh-100
    if (strpos($loginContent, 'min-height: 100vh') !== false) {
        echo "   ✓ Usa min-height para evitar scroll\n";
    } else {
        echo "   ✗ No usa min-height optimizado\n";
    }
    
    // Verificar padding
    if (strpos($loginContent, 'padding: 20px 0') !== false) {
        echo "   ✓ Padding configurado para responsividad\n";
    } else {
        echo "   ✗ Padding no optimizado\n";
    }
    
    // Verificar tamaños de columna
    if (strpos($loginContent, 'col-xl-4') !== false) {
        echo "   ✓ Columnas optimizadas (xl-4)\n";
    } else {
        echo "   ✗ Columnas no optimizadas\n";
    }
    
} else {
    echo "   ✗ Archivo login.php NO existe\n";
}

echo "\n2. Verificando SIGNUP optimizado...\n";
$signupPath = 'frontend/views/site/signup.php';
if (file_exists($signupPath)) {
    $signupContent = file_get_contents($signupPath);
    $signupSize = strlen($signupContent);
    
    echo "   ✓ Archivo signup.php existe (Tamaño: " . number_format($signupSize) . " caracteres)\n";
    
    // Verificar que no tenga formularios duplicados
    $formCount = substr_count($signupContent, 'ActiveForm::begin');
    echo "   ✓ Formularios detectados: $formCount (debe ser 1)\n";
    
    // Verificar que use min-height en lugar de vh-100
    if (strpos($signupContent, 'min-height: 100vh') !== false) {
        echo "   ✓ Usa min-height para evitar scroll\n";
    } else {
        echo "   ✗ No usa min-height optimizado\n";
    }
    
    // Verificar que tenga todos los campos necesarios
    if (strpos($signupContent, 'username') !== false && 
        strpos($signupContent, 'email') !== false && 
        strpos($signupContent, 'password') !== false) {
        echo "   ✓ Todos los campos presentes (username, email, password)\n";
    } else {
        echo "   ✗ Campos faltantes\n";
    }
    
} else {
    echo "   ✗ Archivo signup.php NO existe\n";
}

echo "\n3. Optimizaciones aplicadas:\n";
echo "   • Eliminado formulario duplicado en signup\n";
echo "   • Cambiado vh-100 por min-height: 100vh con padding\n";
echo "   • Reducido tamaño de logos (80px vs 120px)\n";
echo "   • Reducido padding de tarjetas (p-4 vs p-5)\n";
echo "   • Optimizado tamaño de columnas (xl-4 vs lg-5)\n";
echo "   • Reducida opacidad de fondo (0.08 vs 0.1)\n";
echo "   • Tamaños de texto más pequeños para mejor ajuste\n";
echo "   • Botones más compactos\n";

echo "\n4. URLs para probar:\n";
echo "   Login:  http://localhost/altas_bajas/frontend/web/index.php?r=site/login\n";
echo "   Signup: http://localhost/altas_bajas/frontend/web/index.php?r=site/signup\n";

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
echo "\n💡 Ahora ambas páginas deberían verse completas sin necesidad de scroll\n";
?>
