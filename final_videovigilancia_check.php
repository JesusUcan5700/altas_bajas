<?php
// Final verification script for VideoVigilancia
echo "=== FINAL VIDEOVIGILANCIA VERIFICATION ===" . PHP_EOL;

// Check view file for correct property usage
$viewFile = 'frontend/views/site/videovigilancia/listar.php';
if (file_exists($viewFile)) {
    $viewContent = file_get_contents($viewFile);
    
    // Check for incorrect property usage
    if (preg_match('/\$\w+->Estado/', $viewContent)) {
        echo "1. View file contains ->Estado: FOUND - NEEDS FIXING" . PHP_EOL;
    } else {
        echo "1. View file ->Estado references: CLEAN" . PHP_EOL;
    }
    
    // Check for correct property usage
    if (preg_match('/\$\w+->ESTADO/', $viewContent)) {
        echo "2. View file contains ->ESTADO: FOUND - CORRECT" . PHP_EOL;
    } else {
        echo "2. View file ->ESTADO references: NOT FOUND" . PHP_EOL;
    }
    
    // Check for modal
    if (strpos($viewContent, 'modalEquiposDanados') !== false) {
        echo "3. Damaged equipment modal: FOUND" . PHP_EOL;
    } else {
        echo "3. Damaged equipment modal: NOT FOUND" . PHP_EOL;
    }
}

// Check model file
$modelFile = 'frontend/models/VideoVigilancia.php';
if (file_exists($modelFile)) {
    $modelContent = file_get_contents($modelFile);
    
    // Check property definition
    if (preg_match('/@property string \$ESTADO/', $modelContent)) {
        echo "4. Model property definition: @property string \$ESTADO - CORRECT" . PHP_EOL;
    } else {
        echo "4. Model property definition: NEEDS CHECKING" . PHP_EOL;
    }
    
    // Check methods use correct field
    if (preg_match('/where\(\[\'ESTADO\' => self::ESTADO_DANADO\]\)/', $modelContent)) {
        echo "5. Model methods use ESTADO field: CORRECT" . PHP_EOL;
    } else {
        echo "5. Model methods field usage: NEEDS CHECKING" . PHP_EOL;
    }
}

// Check controller
$controllerFile = 'frontend/controllers/SiteController.php';
if (file_exists($controllerFile)) {
    $controllerContent = file_get_contents($controllerFile);
    
    // Check SQL direct usage
    if (strpos($controllerContent, 'video_vigilancia') !== false && 
        strpos($controllerContent, 'idVIDEO_VIGILANCIA') !== false) {
        echo "6. Controller SQL mapping: CORRECT" . PHP_EOL;
    } else {
        echo "6. Controller SQL mapping: NEEDS CHECKING" . PHP_EOL;
    }
}

echo "\n=== VERIFICATION COMPLETED ===" . PHP_EOL;
