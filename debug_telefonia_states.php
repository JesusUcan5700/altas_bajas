<?php
// Simple debug script without full Yii initialization
echo "=== DEBUGGING TELEFONIA STATES ===" . PHP_EOL;

// Check if file exists and read constants
$modelFile = 'frontend/models/Telefonia.php';
if (file_exists($modelFile)) {
    $content = file_get_contents($modelFile);
    
    // Extract constants
    if (preg_match('/const ESTADO_DANADO = \'([^\']+)\';/', $content, $matches)) {
        echo "1. ESTADO_DANADO constant: '" . $matches[1] . "'" . PHP_EOL;
    }
    
    if (preg_match('/const ESTADO_BAJA = \'([^\']+)\';/', $content, $matches)) {
        echo "2. ESTADO_BAJA constant: '" . $matches[1] . "'" . PHP_EOL;
    }
    
    // Check validation rules
    if (preg_match('/\[\'ESTADO\'\], \'in\', \'range\' => \[(.*?)\]/', $content, $matches)) {
        echo "3. Validation range found in code" . PHP_EOL;
        echo "   Range content: " . trim($matches[1]) . PHP_EOL;
    }
    
    // Check getEquiposDanados method
    if (preg_match('/where\(\[\'ESTADO\' => ([^\]]+)\]\)/', $content, $matches)) {
        echo "4. getEquiposDanados uses: " . trim($matches[1]) . PHP_EOL;
    }
    
} else {
    echo "Model file not found: $modelFile" . PHP_EOL;
}

// Check view file
$viewFile = 'frontend/views/site/telefonia/listar.php';
if (file_exists($viewFile)) {
    $viewContent = file_get_contents($viewFile);
    
    // Check if modal exists
    if (strpos($viewContent, 'modalEquiposDanados') !== false) {
        echo "5. Modal for damaged equipment: FOUND in view" . PHP_EOL;
    } else {
        echo "5. Modal for damaged equipment: NOT FOUND in view" . PHP_EOL;
    }
    
    // Check form action
    if (preg_match('/action="([^"]*cambiar-estado-masivo[^"]*)"/', $viewContent, $matches)) {
        echo "6. Form action found: " . $matches[1] . PHP_EOL;
    }
    
    // Check model value
    if (preg_match('/<input type="hidden" name="modelo" value="([^"]+)"/', $viewContent, $matches)) {
        echo "7. Model value in form: " . $matches[1] . PHP_EOL;
    }
    
} else {
    echo "View file not found: $viewFile" . PHP_EOL;
}

// Check controller
$controllerFile = 'frontend/controllers/SiteController.php';
if (file_exists($controllerFile)) {
    $controllerContent = file_get_contents($controllerFile);
    
    // Check if Telefonia is in the SQL direct block
    if (preg_match('/if \(\$modelo === \'Monitor\' \|\| \$modelo === \'Telefonia\'\)/', $controllerContent)) {
        echo "8. Controller: Telefonia uses SQL direct approach - FOUND" . PHP_EOL;
    } else {
        echo "8. Controller: Telefonia uses SQL direct approach - NOT FOUND" . PHP_EOL;
    }
    
    // Check redirect mapping
    if (preg_match('/\'Telefonia\' => \'([^\']+)\'/', $controllerContent, $matches)) {
        echo "9. Redirect mapping for Telefonia: " . $matches[1] . PHP_EOL;
    }
    
} else {
    echo "Controller file not found: $controllerFile" . PHP_EOL;
}

echo "\n=== DEBUG COMPLETED ===" . PHP_EOL;
