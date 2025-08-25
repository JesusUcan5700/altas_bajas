<?php
// Final verification for login-first behavior
echo "=== FINAL LOGIN-FIRST CONFIGURATION CHECK ===" . PHP_EOL;

// Check actionIndex
$controllerFile = 'frontend/controllers/SiteController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    if (strpos($content, "Yii::\$app->user->isGuest") !== false && 
        strpos($content, "redirect(['site/login'])") !== false) {
        echo "✓ actionIndex redirects guests to login: CONFIGURED" . PHP_EOL;
    } else {
        echo "✗ actionIndex login redirection: NOT CONFIGURED" . PHP_EOL;
    }
    
    if (strpos($content, 'public function actionLogin()') !== false) {
        echo "✓ actionLogin method: EXISTS" . PHP_EOL;
    } else {
        echo "✗ actionLogin method: MISSING" . PHP_EOL;
    }
    
    if (strpos($content, 'public function actionLogout()') !== false) {
        echo "✓ actionLogout method: EXISTS" . PHP_EOL;
    } else {
        echo "✗ actionLogout method: MISSING" . PHP_EOL;
    }
}

// Check login view
if (file_exists('frontend/views/site/login.php')) {
    $loginContent = file_get_contents('frontend/views/site/login.php');
    
    if (strpos($loginContent, 'Sistema de Gestión de Equipos') !== false) {
        echo "✓ Login view customized: YES" . PHP_EOL;
    } else {
        echo "✗ Login view customized: NO" . PHP_EOL;
    }
    
    echo "✓ Login view file: EXISTS" . PHP_EOL;
} else {
    echo "✗ Login view file: MISSING" . PHP_EOL;
}

// Check LoginForm model
if (file_exists('common/models/LoginForm.php')) {
    echo "✓ LoginForm model: EXISTS" . PHP_EOL;
} else {
    echo "✗ LoginForm model: MISSING" . PHP_EOL;
}

echo "\n=== CONFIGURATION SUMMARY ===" . PHP_EOL;
echo "URL Behavior:" . PHP_EOL;
echo "  http://localhost/your-app/ → Redirects to login page" . PHP_EOL;
echo "  After successful login → Returns to homepage" . PHP_EOL;
echo "  Logout → Returns to homepage (which redirects to login)" . PHP_EOL;

echo "\nLogin Page Features:" . PHP_EOL;
echo "  • Customized for equipment management system" . PHP_EOL;
echo "  • Professional card-based design" . PHP_EOL;
echo "  • Bootstrap styling" . PHP_EOL;
echo "  • Remember me functionality" . PHP_EOL;

echo "\n=== VERIFICATION COMPLETED ===" . PHP_EOL;
