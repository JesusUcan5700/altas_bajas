<?php
// Test script to verify authentication actions
echo "=== AUTHENTICATION ACTIONS VERIFICATION ===" . PHP_EOL;

// Check controller file
$controllerFile = 'frontend/controllers/SiteController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Check actionLogin
    if (strpos($content, 'public function actionLogin()') !== false) {
        echo "1. actionLogin method: FOUND" . PHP_EOL;
    } else {
        echo "1. actionLogin method: NOT FOUND" . PHP_EOL;
    }
    
    // Check actionLogout
    if (strpos($content, 'public function actionLogout()') !== false) {
        echo "2. actionLogout method: FOUND" . PHP_EOL;
    } else {
        echo "2. actionLogout method: NOT FOUND" . PHP_EOL;
    }
    
    // Check access rules for login
    if (preg_match('/\'only\' => \[\'logout\', \'signup\', \'login\'\]/', $content)) {
        echo "3. Access rules for login: CONFIGURED" . PHP_EOL;
    } else {
        echo "3. Access rules for login: NEEDS CHECKING" . PHP_EOL;
    }
    
    // Check verb filter for logout
    if (preg_match('/\'logout\' => \[\'post\'\]/', $content)) {
        echo "4. POST verb for logout: CONFIGURED" . PHP_EOL;
    } else {
        echo "4. POST verb for logout: NEEDS CHECKING" . PHP_EOL;
    }
}

// Check if LoginForm exists
if (file_exists('common/models/LoginForm.php')) {
    echo "5. LoginForm model: EXISTS" . PHP_EOL;
} else {
    echo "5. LoginForm model: NOT FOUND" . PHP_EOL;
}

// Check if login view exists
if (file_exists('frontend/views/site/login.php')) {
    echo "6. Login view: EXISTS" . PHP_EOL;
} else {
    echo "6. Login view: NOT FOUND" . PHP_EOL;
}

// Check layout logout form
$layoutFile = 'frontend/views/layouts/main.php';
if (file_exists($layoutFile)) {
    $layoutContent = file_get_contents($layoutFile);
    
    if (strpos($layoutContent, "['/site/logout']") !== false) {
        echo "7. Logout form in layout: CONFIGURED" . PHP_EOL;
    } else {
        echo "7. Logout form in layout: NEEDS CHECKING" . PHP_EOL;
    }
    
    if (strpos($layoutContent, "['/site/login']") !== false) {
        echo "8. Login link in layout: CONFIGURED" . PHP_EOL;
    } else {
        echo "8. Login link in layout: NEEDS CHECKING" . PHP_EOL;
    }
}

echo "\n=== VERIFICATION COMPLETED ===" . PHP_EOL;
echo "Try accessing: /site/login and /site/logout" . PHP_EOL;
