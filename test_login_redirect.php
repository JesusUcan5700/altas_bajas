<?php
// Test script to verify login redirection
echo "=== LOGIN REDIRECTION VERIFICATION ===" . PHP_EOL;

// Check SiteController actionIndex
$controllerFile = 'frontend/controllers/SiteController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Check if actionIndex redirects to login for guests
    if (preg_match('/if \(Yii::\$app->user->isGuest\).*redirect\(\[\'site\/login\'\]\)/', $content)) {
        echo "1. actionIndex redirects guests to login: CONFIGURED" . PHP_EOL;
    } else {
        echo "1. actionIndex login redirection: NEEDS CHECKING" . PHP_EOL;
    }
    
    // Check if actionLogin exists
    if (strpos($content, 'public function actionLogin()') !== false) {
        echo "2. actionLogin method: EXISTS" . PHP_EOL;
    } else {
        echo "2. actionLogin method: NOT FOUND" . PHP_EOL;
    }
    
    // Check access rules include index
    if (preg_match('/\'only\' => \[.*\'login\'.*\]/', $content)) {
        echo "3. Access control for login: CONFIGURED" . PHP_EOL;
    } else {
        echo "3. Access control: NEEDS CHECKING" . PHP_EOL;
    }
}

// Check if login view exists
if (file_exists('frontend/views/site/login.php')) {
    echo "4. Login view file: EXISTS" . PHP_EOL;
} else {
    echo "4. Login view file: NOT FOUND" . PHP_EOL;
}

// Check if LoginForm model exists
if (file_exists('common/models/LoginForm.php')) {
    echo "5. LoginForm model: EXISTS" . PHP_EOL;
} else {
    echo "5. LoginForm model: NOT FOUND" . PHP_EOL;
}

// Check layout for guest handling
$layoutFile = 'frontend/views/layouts/main.php';
if (file_exists($layoutFile)) {
    $layoutContent = file_get_contents($layoutFile);
    
    if (strpos($layoutContent, 'Yii::$app->user->isGuest') !== false) {
        echo "6. Layout handles guest users: CONFIGURED" . PHP_EOL;
    } else {
        echo "6. Layout guest handling: NEEDS CHECKING" . PHP_EOL;
    }
}

echo "\n=== VERIFICATION COMPLETED ===" . PHP_EOL;
echo "Expected behavior:" . PHP_EOL;
echo "1. Visit localhost -> Redirect to /site/login" . PHP_EOL;
echo "2. After login -> Redirect to homepage" . PHP_EOL;
echo "3. If not logged in -> Always redirect to login" . PHP_EOL;
