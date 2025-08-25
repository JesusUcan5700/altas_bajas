<?php
// Test de auditoría para memoria RAM - versión web
header('Content-Type: text/plain; charset=utf-8');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require '../../vendor/autoload.php';
require '../../vendor/yiisoft/yii2/Yii.php';

// Importar las clases necesarias
use frontend\models\Ram;
use common\models\User;

$config = yii\helpers\ArrayHelper::merge(
    require '../../common/config/main.php',
    require '../../common/config/main-local.php',
    require '../config/main.php',
    require '../config/main-local.php'
);

try {
    new yii\web\Application($config);

    echo "=== TEST DEL SISTEMA DE AUDITORÍA PARA MEMORIA RAM ===\n\n";
    
    // Verificar conexión a la base de datos
    $connection = Yii::$app->db;
    echo "1. Conexión a la base de datos: ";
    $count = $connection->createCommand("SELECT COUNT(*) FROM memoria_ram")->queryScalar();
    echo "OK - $count módulos de memoria RAM encontrados\n\n";
    
    if ($count == 0) {
        echo "No hay módulos de memoria RAM en la base de datos para probar.\n";
        exit;
    }
    
    // Buscar el primer módulo de memoria RAM
    $ram = Ram::find()->one();
    
    echo "2. Módulo de memoria RAM de prueba encontrado:\n";
    echo "   ID: " . $ram->idRAM . "\n";
    echo "   Marca: " . $ram->MARCA . "\n";
    echo "   Modelo: " . $ram->MODELO . "\n";
    echo "   Capacidad: " . $ram->CAPACIDAD . " GB\n";
    echo "   Tipo DDR: " . $ram->TIPO_DDR . "\n\n";
    
    echo "3. Estado actual de auditoría:\n";
    echo "   Fecha creación: " . ($ram->fecha_creacion ?: 'No definida') . "\n";
    echo "   Fecha última edición: " . ($ram->fecha_ultima_edicion ?: 'No definida') . "\n";
    echo "   Último editor: " . ($ram->ultimo_editor ?: 'No definido') . "\n\n";
    
    // Probar métodos de auditoría
    echo "4. Métodos de auditoría:\n";
    echo "   Fecha creación formateada: " . $ram->getFechaCreacionFormateada() . "\n";
    echo "   Fecha última edición formateada: " . $ram->getFechaUltimaEdicionFormateada() . "\n";
    echo "   Tiempo activo: " . $ram->getTiempoActivo() . "\n";
    echo "   Tiempo desde última edición: " . $ram->getTiempoUltimaEdicion() . "\n";
    echo "   Info último editor: " . $ram->getInfoUltimoEditor() . "\n\n";
    
    // Probar relación con usuario
    if ($ram->ultimo_editor) {
        $usuario = $ram->ultimoEditor;
        if ($usuario) {
            echo "5. Información del último editor (relación ActiveRecord):\n";
            echo "   Username: " . $usuario->username . "\n";
            echo "   Email: " . $usuario->email . "\n";
        } else {
            echo "5. El usuario '{$ram->ultimo_editor}' no existe en la tabla de usuarios.\n";
        }
    } else {
        echo "5. No hay último editor definido.\n";
    }
    echo "\n";
    
    // Simular una edición
    echo "6. Simulando edición...\n";
    $descripcionOriginal = $ram->Descripcion;
    $ram->Descripcion = "Descripción actualizada por prueba - " . date('Y-m-d H:i:s');
    
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
    
    $fechaAntesGuardar = $ram->fecha_ultima_edicion;
    
    if ($ram->save()) {
        echo "   ✓ Módulo de memoria RAM guardado exitosamente\n";
        echo "   Fecha anterior: " . ($fechaAntesGuardar ?: 'NULL') . "\n";
        echo "   Fecha nueva: " . $ram->fecha_ultima_edicion . "\n";
        echo "   Último editor: " . $ram->ultimo_editor . "\n";
        echo "   Info completa del editor: " . $ram->getInfoUltimoEditor() . "\n";
        echo "   Tiempo activo total: " . $ram->getTiempoActivo() . "\n";
        echo "   Tiempo desde edición: " . $ram->getTiempoUltimaEdicion() . "\n";
        
        // Restaurar descripción original
        $ram->Descripcion = $descripcionOriginal;
        $ram->save(false);
        echo "   ✓ Descripción restaurada\n";
    } else {
        echo "   ✗ Error al guardar el módulo de memoria RAM:\n";
        foreach ($ram->getErrors() as $field => $errors) {
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
