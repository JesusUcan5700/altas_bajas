<?php
// Simple debug script for VideoVigilancia
echo "=== DEBUGGING VIDEOVIGILANCIA FIXES ===" . PHP_EOL;

// Check model file
$modelFile = 'frontend/models/VideoVigilancia.php';
if (file_exists($modelFile)) {
    $content = file_get_contents($modelFile);
    
    // Check constants
    if (preg_match('/const ESTADO_BAJA = \'([^\']+)\';/', $content, $matches)) {
        echo "1. ESTADO_BAJA constant: '" . $matches[1] . "' - FOUND" . PHP_EOL;
    } else {
        echo "1. ESTADO_BAJA constant: NOT FOUND" . PHP_EOL;
    }
    
    // Check validation includes BAJA
    if (strpos($content, 'self::ESTADO_BAJA') !== false) {
        echo "2. ESTADO_BAJA in validation: FOUND" . PHP_EOL;
    } else {
        echo "2. ESTADO_BAJA in validation: NOT FOUND" . PHP_EOL;
    }
    
    // Check getEquiposDanados uses ESTADO field
    if (preg_match('/where\(\[\'ESTADO\' => self::ESTADO_DANADO\]\)/', $content)) {
        echo "3. getEquiposDanados uses ESTADO field: CORRECT" . PHP_EOL;
    } else {
        echo "3. getEquiposDanados field: NEEDS CHECKING" . PHP_EOL;
    }
    
    // Check getEstados method
    if (preg_match('/self::ESTADO_BAJA => \'BAJA\'/', $content)) {
        echo "4. getEstados includes ESTADO_BAJA constant: CORRECT" . PHP_EOL;
    } else {
        echo "4. getEstados ESTADO_BAJA: NEEDS CHECKING" . PHP_EOL;
    }
}

// Check controller
$controllerFile = 'frontend/controllers/SiteController.php';
if (file_exists($controllerFile)) {
    $controllerContent = file_get_contents($controllerFile);
    
    // Check if Videovigilancia is in SQL direct block
    if (preg_match('/if \(\$modelo === \'Monitor\' \|\| \$modelo === \'Telefonia\' \|\| \$modelo === \'Videovigilancia\'\)/', $controllerContent)) {
        echo "5. Controller: Videovigilancia in SQL direct block - FOUND" . PHP_EOL;
    } else {
        echo "5. Controller: Videovigilancia in SQL direct block - NOT FOUND" . PHP_EOL;
    }
    
    // Check table mapping
    if (preg_match('/\$tableName = \'video_vigilancia\';/', $controllerContent)) {
        echo "6. Controller: video_vigilancia table mapping - FOUND" . PHP_EOL;
    } else {
        echo "6. Controller: video_vigilancia table mapping - NOT FOUND" . PHP_EOL;
    }
    
    // Check primary key mapping  
    if (preg_match('/\$primaryKey = \'idVIDEO_VIGILANCIA\';/', $controllerContent)) {
        echo "7. Controller: idVIDEO_VIGILANCIA primary key - FOUND" . PHP_EOL;
    } else {
        echo "7. Controller: idVIDEO_VIGILANCIA primary key - NOT FOUND" . PHP_EOL;
    }
}

echo "\n=== FIXES VERIFICATION COMPLETED ===" . PHP_EOL;
