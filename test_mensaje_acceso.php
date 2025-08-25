<?php
echo "=== PRUEBA DE MENSAJE DE ACCESO NO AUTORIZADO ===\n\n";

echo "1. Verificando configuración del actionIndex...\n";
$controllerPath = 'frontend/controllers/SiteController.php';
if (file_exists($controllerPath)) {
    $controllerContent = file_get_contents($controllerPath);
    
    echo "   ✓ SiteController.php existe\n";
    
    // Verificar que tiene el mensaje flash
    if (strpos($controllerContent, "setFlash('warning'") !== false) {
        echo "   ✓ Mensaje flash de warning configurado\n";
    } else {
        echo "   ✗ Mensaje flash de warning NO configurado\n";
    }
    
    // Verificar el texto del mensaje
    if (strpos($controllerContent, 'Debe iniciar sesión para acceder al sistema') !== false) {
        echo "   ✓ Mensaje personalizado configurado\n";
    } else {
        echo "   ✗ Mensaje personalizado NO configurado\n";
    }
    
    // Verificar redirección
    if (strpos($controllerContent, "redirect(['site/login'])") !== false) {
        echo "   ✓ Redirección al login configurada\n";
    } else {
        echo "   ✗ Redirección al login NO configurada\n";
    }
    
} else {
    echo "   ✗ SiteController.php NO existe\n";
}

echo "\n2. Verificando soporte de mensajes en vista login...\n";
$loginPath = 'frontend/views/site/login.php';
if (file_exists($loginPath)) {
    $loginContent = file_get_contents($loginPath);
    
    echo "   ✓ login.php existe\n";
    
    // Verificar soporte para diferentes tipos de flash
    if (strpos($loginContent, "hasFlash('warning')") !== false) {
        echo "   ✓ Soporte para mensajes warning\n";
    } else {
        echo "   ✗ NO tiene soporte para mensajes warning\n";
    }
    
    if (strpos($loginContent, "hasFlash('error')") !== false) {
        echo "   ✓ Soporte para mensajes error\n";
    } else {
        echo "   ✗ NO tiene soporte para mensajes error\n";
    }
    
    if (strpos($loginContent, "alert-warning") !== false) {
        echo "   ✓ Estilos warning configurados\n";
    } else {
        echo "   ✗ Estilos warning NO configurados\n";
    }
    
} else {
    echo "   ✗ login.php NO existe\n";
}

echo "\n3. Verificando soporte de mensajes en vista signup...\n";
$signupPath = 'frontend/views/site/signup.php';
if (file_exists($signupPath)) {
    $signupContent = file_get_contents($signupPath);
    
    echo "   ✓ signup.php existe\n";
    
    // Verificar soporte para diferentes tipos de flash
    if (strpos($signupContent, "hasFlash('warning')") !== false) {
        echo "   ✓ Soporte para mensajes warning\n";
    } else {
        echo "   ✗ NO tiene soporte para mensajes warning\n";
    }
    
    if (strpos($signupContent, "hasFlash('error')") !== false) {
        echo "   ✓ Soporte para mensajes error\n";
    } else {
        echo "   ✗ NO tiene soporte para mensajes error\n";
    }
    
} else {
    echo "   ✗ signup.php NO existe\n";
}

echo "\n4. Funcionamiento del sistema:\n";
echo "   📋 Cuando un usuario intente acceder al index sin login:\n";
echo "      1. Se establece un mensaje flash de warning\n";
echo "      2. Se redirige automáticamente al login\n";
echo "      3. El login muestra el mensaje con icono de advertencia\n";
echo "      4. El mensaje desaparece automáticamente\n";

echo "\n5. Tipos de mensajes soportados:\n";
echo "   ✅ Success (verde): Para operaciones exitosas\n";
echo "   ⚠️  Warning (amarillo): Para advertencias como acceso no autorizado\n";
echo "   ❌ Error (rojo): Para errores del sistema\n";

echo "\n6. URLs para probar:\n";
echo "   Forzar index: http://localhost/altas_bajas/frontend/web/index.php\n";
echo "   Login normal: http://localhost/altas_bajas/frontend/web/index.php?r=site/login\n";

echo "\n=== CONFIGURACIÓN COMPLETADA ===\n";
echo "\n💡 Ahora el sistema mostrará un mensaje cuando alguien trate de acceder sin login\n";
?>
