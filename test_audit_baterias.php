<?php
// Script para probar el sistema de auditoría de baterias
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = yii\helpers\ArrayHelper::merge(
    require 'common/config/main.php',
    require 'common/config/main-local.php',
    require 'frontend/config/main.php',
    require 'frontend/config/main-local.php'
);

(new yii\web\Application($config));

use frontend\models\Bateria;
use common\models\User;

try {
    // Buscar una batería existente
    $bateria = Bateria::find()->one();
    
    if (!$bateria) {
        echo "No hay baterías en la base de datos.\n";
        exit;
    }
    
    echo "=== INFORMACIÓN DE BATERÍA ===\n";
    echo "ID: " . $bateria->idBateria . "\n";
    echo "Marca: " . $bateria->MARCA . "\n";
    echo "Modelo: " . $bateria->MODELO . "\n";
    echo "Número Inventario: " . $bateria->NUMERO_INVENTARIO . "\n";
    echo "\n";
    
    echo "=== AUDITORÍA ACTUAL ===\n";
    echo "Fecha Creación: " . ($bateria->fecha_creacion ?: 'No definida') . "\n";
    echo "Fecha Última Edición: " . ($bateria->fecha_ultima_edicion ?: 'No definida') . "\n";
    echo "Último Editor: " . ($bateria->ultimo_editor ?: 'No definido') . "\n";
    echo "\n";
    
    // Probar la relación con usuario
    if ($bateria->ultimo_editor) {
        $usuario = $bateria->ultimoEditor;
        if ($usuario) {
            echo "=== INFORMACIÓN DEL ÚLTIMO EDITOR ===\n";
            echo "Usuario: " . $usuario->username . "\n";
            echo "Email: " . $usuario->email . "\n";
            echo "Info completa: " . $bateria->getInfoUltimoEditor() . "\n";
        } else {
            echo "Usuario último editor no encontrado en la tabla de usuarios.\n";
        }
    }
    echo "\n";
    
    // Probar fechas formateadas
    echo "=== FECHAS FORMATEADAS ===\n";
    echo "Fecha Creación: " . $bateria->getFechaCreacionFormateada() . "\n";
    echo "Fecha Última Edición: " . $bateria->getFechaUltimaEdicionFormateada() . "\n";
    echo "\n";
    
    // Simular una edición
    echo "=== SIMULANDO EDICIÓN ===\n";
    $descripcionOriginal = $bateria->DESCRIPCION;
    $bateria->DESCRIPCION = "Descripción actualizada - " . date('Y-m-d H:i:s');
    
    // Simular un usuario logueado (normalmente esto se haría a través de la sesión)
    $usuarios = User::find()->all();
    if (!empty($usuarios)) {
        $usuarioTest = $usuarios[0];
        echo "Simulando edición con usuario: " . $usuarioTest->username . "\n";
        
        // Manualmente establecer el último editor para la prueba
        $bateria->ultimo_editor = $usuarioTest->username;
    }
    
    if ($bateria->save()) {
        echo "Batería actualizada exitosamente.\n";
        echo "Nueva fecha última edición: " . $bateria->fecha_ultima_edicion . "\n";
        echo "Nuevo último editor: " . $bateria->ultimo_editor . "\n";
        echo "Info del editor: " . $bateria->getInfoUltimoEditor() . "\n";
    } else {
        echo "Error al actualizar la batería:\n";
        foreach ($bateria->getErrors() as $field => $errors) {
            echo "- $field: " . implode(', ', $errors) . "\n";
        }
    }
    
    // Restaurar descripción original
    $bateria->DESCRIPCION = $descripcionOriginal;
    $bateria->save(false); // Sin validación para restaurar rápido
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
