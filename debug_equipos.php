<?php
// Archivo temporal para debuggear la carga de datos

// Configurar la aplicación Yii2
require_once('vendor/autoload.php');
require_once('common/config/bootstrap.php');
require_once('frontend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require('common/config/main.php'),
    require('common/config/main-local.php'),
    require('frontend/config/main.php'),
    require('frontend/config/main-local.php')
);

$app = new yii\web\Application($config);

// Simular la carga de datos como lo hace el controlador
function cargarEquiposPorCategoria($categoria) {
    $modelMap = [
        'nobreak' => frontend\models\Nobreak::class,
        'computo' => frontend\models\Equipo::class,
        'impresora' => frontend\models\Impresora::class,
    ];
    
    if (!isset($modelMap[$categoria])) {
        return [];
    }
    
    $modelClass = $modelMap[$categoria];
    $equipos = $modelClass::find()->all();
    
    $resultado = [];
    foreach ($equipos as $equipo) {
        // Debug: mostrar atributos del equipo
        echo "Equipo encontrado - Atributos: " . print_r($equipo->attributes, true) . "\n";
        echo "Primary Key: " . print_r($equipo->getPrimaryKey(), true) . "\n";
        
        $resultado[] = [
            'id' => $equipo->getPrimaryKey(),
            'marca' => $equipo->MARCA ?? 'N/A',
            'modelo' => $equipo->MODELO ?? 'N/A',
            'numero_serie' => $equipo->NUMERO_SERIE ?? $equipo->NUM_SERIE ?? 'N/A',
            'estado' => ucfirst(strtolower($equipo->ESTADO ?? $equipo->Estado ?? 'N/A')),
            'ubicacion' => $equipo->ubicacion_edificio ?? 'N/A',
            'data' => $equipo->attributes
        ];
    }
    
    return $resultado;
}

// Probar con nobreak
echo "=== PROBANDO NOBREAK ===\n";
$resultadoNobreak = cargarEquiposPorCategoria('nobreak');
echo "Resultado final: " . print_r($resultadoNobreak, true) . "\n";

echo "\n=== PROBANDO COMPUTO ===\n";
$resultadoComputo = cargarEquiposPorCategoria('computo');
echo "Resultado final: " . print_r($resultadoComputo, true) . "\n";

echo "\n=== PROBANDO IMPRESORA ===\n";
$resultadoImpresora = cargarEquiposPorCategoria('impresora');
echo "Resultado final: " . print_r($resultadoImpresora, true) . "\n";
