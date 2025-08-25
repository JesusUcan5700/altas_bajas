<?php
// Script de debug para verificar el cálculo del tiempo activo
require_once 'vendor/autoload.php';

// Cargar configuración de Yii
$config = require 'frontend/config/main.php';
$application = new yii\web\Application($config);

// Obtener algunos equipos de muestra
$equipos = \frontend\models\Equipo::find()->limit(5)->all();

echo "=== DEBUG TIEMPO ACTIVO ===\n\n";

foreach ($equipos as $equipo) {
    echo "ID: " . $equipo->idEQUIPO . "\n";
    echo "Fecha de emisión: " . ($equipo->EMISION_INVENTARIO ?: 'NULL') . "\n";
    echo "Tipo de dato: " . gettype($equipo->EMISION_INVENTARIO) . "\n";
    
    if (!empty($equipo->EMISION_INVENTARIO)) {
        // Debug paso a paso
        try {
            $fechaEmision = new \DateTime($equipo->EMISION_INVENTARIO);
            $fechaActual = new \DateTime();
            
            echo "Fecha emisión procesada: " . $fechaEmision->format('Y-m-d H:i:s') . "\n";
            echo "Fecha actual: " . $fechaActual->format('Y-m-d H:i:s') . "\n";
            
            $diferencia = $fechaActual->diff($fechaEmision);
            
            echo "Diferencia object: \n";
            echo "  - days: " . $diferencia->days . "\n";
            echo "  - y: " . $diferencia->y . "\n";
            echo "  - m: " . $diferencia->m . "\n";
            echo "  - d: " . $diferencia->d . "\n";
            echo "  - invert: " . $diferencia->invert . "\n";
            
            echo "Días activo (método): " . $equipo->getDiasActivo() . "\n";
            echo "Años activo (método): " . $equipo->getAnosActivo() . "\n";
            
        } catch (\Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}
?>
