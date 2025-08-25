<?php
echo "=== VERIFICACIÓN DE LOGOS EN PÁGINAS ===\n\n";

// Verificar que los archivos de logo existen
$logoPath = 'frontend/views/site/logo.jpg';
$logo1Path = 'frontend/views/site/logo1.jpg';

echo "1. Verificando archivos de logo...\n";
if (file_exists($logoPath)) {
    $logoSize = filesize($logoPath);
    echo "✓ logo.jpg existe (Tamaño: " . number_format($logoSize / 1024, 2) . " KB)\n";
} else {
    echo "✗ logo.jpg NO existe\n";
}

if (file_exists($logo1Path)) {
    $logo1Size = filesize($logo1Path);
    echo "✓ logo1.jpg existe (Tamaño: " . number_format($logo1Size / 1024, 2) . " KB)\n";
} else {
    echo "✗ logo1.jpg NO existe\n";
}

// Verificar que las vistas contienen las referencias a los logos
echo "\n2. Verificando referencias en las vistas...\n";

$signupViewPath = 'frontend/views/site/signup.php';
if (file_exists($signupViewPath)) {
    $signupContent = file_get_contents($signupViewPath);
    if (strpos($signupContent, 'logo.jpg') !== false && strpos($signupContent, 'logo1.jpg') !== false) {
        echo "✓ signup.php contiene referencias a ambos logos\n";
    } else {
        echo "✗ signup.php NO contiene todas las referencias a los logos\n";
    }
} else {
    echo "✗ signup.php NO existe\n";
}

$loginViewPath = 'frontend/views/site/login.php';
if (file_exists($loginViewPath)) {
    $loginContent = file_get_contents($loginViewPath);
    if (strpos($loginContent, 'logo.jpg') !== false && strpos($loginContent, 'logo1.jpg') !== false) {
        echo "✓ login.php contiene referencias a ambos logos\n";
    } else {
        echo "✗ login.php NO contiene todas las referencias a los logos\n";
    }
} else {
    echo "✗ login.php NO existe\n";
}

echo "\n3. URLs para probar:\n";
echo "   Login:  http://localhost/altas_bajas/frontend/web/index.php?r=site/login\n";
echo "   Signup: http://localhost/altas_bajas/frontend/web/index.php?r=site/signup\n";

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
echo "\nCaracterísticas del diseño:\n";
echo "• Logos en columnas de 3 (logo - icono - logo1)\n";
echo "• Diseño responsive y centrado\n";
echo "• Línea decorativa azul bajo el título\n";
echo "• Fondos degradado y tarjetas con sombras\n";
echo "• Logos redimensionados automáticamente (max 80px)\n";
?>
