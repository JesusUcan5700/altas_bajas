<?php
// Test de auditoría para baterias - versión web
header('Content-Type: text/plain; charset=utf-8');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require '../../vendor/autoload.php';
require '../../vendor/yiisoft/yii2/Yii.php';

// Importar las clases necesarias
use frontend\models\Bateria;
use common\models\User;

$config = yii\helpers\ArrayHelper::merge(
    require '../../common/config/main.php',
    require '../../common/config/main-local.php',
    require '../config/main.php',
    require '../config/main-local.php'
);

try {
    new yii\web\Application($config);

    echo "=== TEST DEL SISTEMA DE AUDITORÍA PARA BATERIAS ===\n\n";
    
    // Verificar conexión a la base de datos
    $connection = Yii::$app->db;
    echo "1. Conexión a la base de datos: ";
    $count = $connection->createCommand("SELECT COUNT(*) FROM baterias")->queryScalar();
    echo "OK - $count baterías encontradas\n\n";
    
    if ($count == 0) {
        echo "No hay baterías en la base de datos para probar.\n";
        exit;
    }
    
    // Buscar la primera batería
    $bateria = Bateria::find()->one();
    
    echo "2. Batería de prueba encontrada:\n";
    echo "   ID: " . $bateria->idBateria . "\n";
    echo "   Marca: " . $bateria->MARCA . "\n";
    echo "   Modelo: " . $bateria->MODELO . "\n";
    echo "   Número Inventario: " . $bateria->NUMERO_INVENTARIO . "\n\n";
    
    echo "3. Estado actual de auditoría:\n";
    echo "   Fecha creación: " . ($bateria->fecha_creacion ?: 'No definida') . "\n";
    echo "   Fecha última edición: " . ($bateria->fecha_ultima_edicion ?: 'No definida') . "\n";
    echo "   Último editor: " . ($bateria->ultimo_editor ?: 'No definido') . "\n\n";
    
    // Probar métodos de auditoría
    echo "4. Métodos de auditoría:\n";
    echo "   Fecha creación formateada: " . $bateria->getFechaCreacionFormateada() . "\n";
    echo "   Fecha última edición formateada: " . $bateria->getFechaUltimaEdicionFormateada() . "\n";
    echo "   Tiempo activo: " . $bateria->getTiempoActivo() . "\n";
    echo "   Tiempo desde última edición: " . $bateria->getTiempoUltimaEdicion() . "\n";
    echo "   Info último editor: " . $bateria->getInfoUltimoEditor() . "\n\n";
    
    // Probar relación con usuario
    if ($bateria->ultimo_editor) {
        $usuario = $bateria->ultimoEditor;
        if ($usuario) {
            echo "5. Información del último editor (relación ActiveRecord):\n";
            echo "   Username: " . $usuario->username . "\n";
            echo "   Email: " . $usuario->email . "\n";
        } else {
            echo "5. El usuario '{$bateria->ultimo_editor}' no existe en la tabla de usuarios.\n";
        }
    } else {
        echo "5. No hay último editor definido.\n";
    }
    echo "\n";
    
    // Simular una edición
    echo "6. Simulando edición...\n";
    $descripcionOriginal = $bateria->DESCRIPCION;
    $bateria->DESCRIPCION = "Descripción actualizada por prueba - " . date('Y-m-d H:i:s');
    
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
    
    $fechaAntesGuardar = $bateria->fecha_ultima_edicion;
    
    if ($bateria->save()) {
        echo "   ✓ Batería guardada exitosamente\n";
        echo "   Fecha anterior: " . ($fechaAntesGuardar ?: 'NULL') . "\n";
        echo "   Fecha nueva: " . $bateria->fecha_ultima_edicion . "\n";
        echo "   Último editor: " . $bateria->ultimo_editor . "\n";
        echo "   Info completa del editor: " . $bateria->getInfoUltimoEditor() . "\n";
        echo "   Tiempo activo total: " . $bateria->getTiempoActivo() . "\n";
        echo "   Tiempo desde edición: " . $bateria->getTiempoUltimaEdicion() . "\n";
        
        // Restaurar descripción original
        $bateria->DESCRIPCION = $descripcionOriginal;
        $bateria->save(false);
        echo "   ✓ Descripción restaurada\n";
    } else {
        echo "   ✗ Error al guardar la batería:\n";
        foreach ($bateria->getErrors() as $field => $errors) {
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
