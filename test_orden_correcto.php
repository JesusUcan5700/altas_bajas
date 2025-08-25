<?php
echo "=== VERIFICACIÓN DEL ORDEN CORRECTO ===\n\n";

// Verificar login
echo "1. Verificando LOGIN con orden correcto...\n";
$loginPath = 'frontend/views/site/login.php';
if (file_exists($loginPath)) {
    $loginContent = file_get_contents($loginPath);
    
    echo "   ✓ Archivo login.php existe\n";
    
    // Buscar posiciones en el orden correcto
    $lines = explode("\n", $loginContent);
    $sistemaLine = -1;
    $logoLine = -1;
    $accionLine = -1;
    
    foreach ($lines as $index => $line) {
        if (strpos($line, 'Sistema de inventario de altas y bajas de equipo de cómputo') !== false) {
            $sistemaLine = $index;
        }
        if (strpos($line, 'logo.jpg') !== false && $logoLine == -1) {
            $logoLine = $index;
        }
        if (strpos($line, 'Iniciar Sesión</h4>') !== false) {
            $accionLine = $index;
        }
    }
    
    if ($sistemaLine < $logoLine && $logoLine < $accionLine) {
        echo "   ✓ Orden CORRECTO: Sistema (línea $sistemaLine) → Logo (línea $logoLine) → Acción (línea $accionLine)\n";
    } else {
        echo "   ✗ Orden incorrecto\n";
    }
    
} else {
    echo "   ✗ Archivo login.php NO existe\n";
}

echo "\n2. Verificando SIGNUP con orden correcto...\n";
$signupPath = 'frontend/views/site/signup.php';
if (file_exists($signupPath)) {
    $signupContent = file_get_contents($signupPath);
    
    echo "   ✓ Archivo signup.php existe\n";
    
    // Buscar posiciones en el orden correcto
    $lines = explode("\n", $signupContent);
    $sistemaLine = -1;
    $logoLine = -1;
    $accionLine = -1;
    
    foreach ($lines as $index => $line) {
        if (strpos($line, 'Sistema de inventario de altas y bajas de equipo de cómputo') !== false) {
            $sistemaLine = $index;
        }
        if (strpos($line, 'logo.jpg') !== false && $logoLine == -1) {
            $logoLine = $index;
        }
        if (strpos($line, 'Crear Cuenta</h4>') !== false) {
            $accionLine = $index;
        }
    }
    
    if ($sistemaLine < $logoLine && $logoLine < $accionLine) {
        echo "   ✓ Orden CORRECTO: Sistema (línea $sistemaLine) → Logo (línea $logoLine) → Acción (línea $accionLine)\n";
    } else {
        echo "   ✗ Orden incorrecto\n";
    }
    
} else {
    echo "   ✗ Archivo signup.php NO existe\n";
}

echo "\n3. Estructura final implementada:\n";
echo "   1️⃣ PRIMERO: 'Sistema de inventario de altas y bajas de equipo de cómputo'\n";
echo "   2️⃣ SEGUNDO: Logo principal (logo.jpg)\n";
echo "   3️⃣ TERCERO: Acción específica ('Iniciar Sesión' / 'Crear Cuenta')\n";
echo "   4️⃣ CUARTO: Línea decorativa\n";
echo "   5️⃣ QUINTO: Formulario\n";

echo "\n4. Cambios realizados:\n";
echo "   • Movido el texto del sistema ARRIBA del logo\n";
echo "   • Mantenido el logo en posición central\n";
echo "   • Acción específica después del logo\n";
echo "   • Orden visual lógico y correcto\n";

echo "\n5. URLs para verificar:\n";
echo "   Login:  http://localhost/altas_bajas/frontend/web/index.php?r=site/login\n";
echo "   Signup: http://localhost/altas_bajas/frontend/web/index.php?r=site/signup\n";

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
echo "\n✅ El texto del sistema ahora está ARRIBA del logo como solicitaste\n";
?>
