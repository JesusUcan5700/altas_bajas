<?php
// Test de auditoría para almacenamiento - versión web
header('Content-Type: text/plain; charset=utf-8');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require '../../vendor/autoload.php';
require '../../vendor/yiisoft/yii2/Yii.php';

// Importar las clases necesarias
use frontend\models\Almacenamiento;
use common\models\User;

$config = yii\helpers\ArrayHelper::merge(
    require '../../common/config/main.php',
    require '../../common/config/main-local.php',
    require '../config/main.php',
    require '../config/main-local.php'
);

try {
    new yii\web\Application($config);

    echo "=== TEST DEL SISTEMA DE AUDITORÍA PARA ALMACENAMIENTO ===\n\n";
    
    // Verificar conexión a la base de datos
    $connection = Yii::$app->db;
    echo "1. Conexión a la base de datos: ";
    $count = $connection->createCommand("SELECT COUNT(*) FROM almacenamiento")->queryScalar();
    echo "OK - $count dispositivos de almacenamiento encontrados\n\n";
    
    if ($count == 0) {
        echo "No hay dispositivos de almacenamiento en la base de datos para probar.\n";
        exit;
    }
    
    // Buscar el primer dispositivo de almacenamiento
    $almacenamiento = Almacenamiento::find()->one();
    
    echo "2. Dispositivo de almacenamiento de prueba encontrado:\n";
    echo "   ID: " . $almacenamiento->idAlmacenamiento . "\n";
    echo "   Marca: " . $almacenamiento->MARCA . "\n";
    echo "   Modelo: " . $almacenamiento->MODELO . "\n";
    echo "   Tipo: " . $almacenamiento->TIPO . "\n\n";
    
    echo "3. Estado actual de auditoría:\n";
    echo "   Fecha creación: " . ($almacenamiento->fecha_creacion ?: 'No definida') . "\n";
    echo "   Fecha última edición: " . ($almacenamiento->fecha_ultima_edicion ?: 'No definida') . "\n";
    echo "   Último editor: " . ($almacenamiento->ultimo_editor ?: 'No definido') . "\n\n";
    
    // Probar métodos de auditoría
    echo "4. Métodos de auditoría:\n";
    echo "   Fecha creación formateada: " . $almacenamiento->getFechaCreacionFormateada() . "\n";
    echo "   Fecha última edición formateada: " . $almacenamiento->getFechaUltimaEdicionFormateada() . "\n";
    echo "   Tiempo activo: " . $almacenamiento->getTiempoActivo() . "\n";
    echo "   Tiempo desde última edición: " . $almacenamiento->getTiempoUltimaEdicion() . "\n";
    echo "   Info último editor: " . $almacenamiento->getInfoUltimoEditor() . "\n\n";
    
    // Probar relación con usuario
    if ($almacenamiento->ultimo_editor) {
        $usuario = $almacenamiento->ultimoEditor;
        if ($usuario) {
            echo "5. Información del último editor (relación ActiveRecord):\n";
            echo "   Username: " . $usuario->username . "\n";
            echo "   Email: " . $usuario->email . "\n";
        } else {
            echo "5. El usuario '{$almacenamiento->ultimo_editor}' no existe en la tabla de usuarios.\n";
        }
    } else {
        echo "5. No hay último editor definido.\n";
    }
    echo "\n";
    
    // Simular una edición
    echo "6. Simulando edición...\n";
    $descripcionOriginal = $almacenamiento->DESCRIPCION;
    $almacenamiento->DESCRIPCION = "Descripción actualizada por prueba - " . date('Y-m-d H:i:s');
    
    // Buscar un usuario para la prueba
    $usuarios = User::find()->all();
    if (!empty($usuarios)) {
        $usuarioTest = $usuarios[0];
        echo "   Usuario de prueba: " . $usuarioTest->username . "\n";
        
        // Simular login del usuario
        Yii::$app->user->login($usuarioTest);
    } else {
        echo "   No hay usuarios en la base de datos, se usará 'Sistema'\n";
    }
    
    $fechaAntesGuardar = $almacenamiento->fecha_ultima_edicion;
    
    if ($almacenamiento->save()) {
        echo "   ✓ Dispositivo de almacenamiento guardado exitosamente\n";
        echo "   Fecha anterior: " . ($fechaAntesGuardar ?: 'NULL') . "\n";
        echo "   Fecha nueva: " . $almacenamiento->fecha_ultima_edicion . "\n";
        echo "   Último editor: " . $almacenamiento->ultimo_editor . "\n";
        echo "   Info completa del editor: " . $almacenamiento->getInfoUltimoEditor() . "\n";
        echo "   Tiempo activo total: " . $almacenamiento->getTiempoActivo() . "\n";
        echo "   Tiempo desde edición: " . $almacenamiento->getTiempoUltimaEdicion() . "\n";
        
        // Restaurar descripción original
        $almacenamiento->DESCRIPCION = $descripcionOriginal;
        $almacenamiento->save(false);
        echo "   ✓ Descripción restaurada\n";
    } else {
        echo "   ✗ Error al guardar el dispositivo de almacenamiento:\n";
        foreach ($almacenamiento->getErrors() as $field => $errors) {
            echo "     - $field: " . implode(', ', $errors) . "\n";
        }
    }
    
    echo "\n=== PRUEBA COMPLETADA ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . " Línea: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
