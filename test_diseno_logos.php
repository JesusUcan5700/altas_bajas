<?php
echo "=== VERIFICACIÓN DEL NUEVO DISEÑO CON LOGOS ===\n\n";

// Verificar estructura de login
echo "1. Verificando página de LOGIN...\n";
$loginPath = 'frontend/views/site/login.php';
if (file_exists($loginPath)) {
    $loginContent = file_get_contents($loginPath);
    
    echo "   ✓ Archivo login.php existe\n";
    
    // Verificar elementos del diseño
    if (strpos($loginContent, 'logo1.jpg') !== false) {
        echo "   ✓ Logo de fondo (logo1.jpg) presente\n";
    } else {
        echo "   ✗ Logo de fondo faltante\n";
    }
    
    if (strpos($loginContent, 'logo.jpg') !== false) {
        echo "   ✓ Logo principal (logo.jpg) presente\n";
    } else {
        echo "   ✗ Logo principal faltante\n";
    }
    
    if (strpos($loginContent, 'username') !== false && strpos($loginContent, 'password') !== false) {
        echo "   ✓ Campos de formulario presentes (username, password)\n";
    } else {
        echo "   ✗ Campos de formulario faltantes\n";
    }
    
    if (strpos($loginContent, 'rememberMe') !== false) {
        echo "   ✓ Campo 'Recordarme' presente\n";
    } else {
        echo "   ✗ Campo 'Recordarme' faltante\n";
    }
    
    if (strpos($loginContent, 'opacity: 0.1') !== false) {
        echo "   ✓ Logo de fondo con transparencia configurada\n";
    } else {
        echo "   ✗ Transparencia de fondo no configurada\n";
    }
} else {
    echo "   ✗ Archivo login.php NO existe\n";
}

echo "\n2. Verificando página de SIGNUP...\n";
$signupPath = 'frontend/views/site/signup.php';
if (file_exists($signupPath)) {
    $signupContent = file_get_contents($signupPath);
    
    echo "   ✓ Archivo signup.php existe\n";
    
    // Verificar elementos del diseño
    if (strpos($signupContent, 'logo1.jpg') !== false) {
        echo "   ✓ Logo de fondo (logo1.jpg) presente\n";
    } else {
        echo "   ✗ Logo de fondo faltante\n";
    }
    
    if (strpos($signupContent, 'logo.jpg') !== false) {
        echo "   ✓ Logo principal (logo.jpg) presente\n";
    } else {
        echo "   ✗ Logo principal faltante\n";
    }
    
    if (strpos($signupContent, 'username') !== false && strpos($signupContent, 'email') !== false && strpos($signupContent, 'password') !== false) {
        echo "   ✓ Campos de formulario presentes (username, email, password)\n";
    } else {
        echo "   ✗ Campos de formulario faltantes\n";
    }
    
    if (strpos($signupContent, 'opacity: 0.1') !== false) {
        echo "   ✓ Logo de fondo con transparencia configurada\n";
    } else {
        echo "   ✗ Transparencia de fondo no configurada\n";
    }
} else {
    echo "   ✗ Archivo signup.php NO existe\n";
}

echo "\n3. Características del nuevo diseño:\n";
echo "   • Logo1.jpg como fondo (transparencia 0.1)\n";
echo "   • Logo.jpg centrado arriba del formulario (120px altura máxima)\n";
echo "   • Formularios completos con todos los campos\n";
echo "   • Diseño responsive y profesional\n";
echo "   • Gradient de fondo mantenido\n";
echo "   • Navegación entre login y signup\n";

echo "\n4. URLs para probar:\n";
echo "   Login:  http://localhost/altas_bajas/frontend/web/index.php?r=site/login\n";
echo "   Signup: http://localhost/altas_bajas/frontend/web/index.php?r=site/signup\n";

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
?>
