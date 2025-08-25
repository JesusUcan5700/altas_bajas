<?php
/**
 * Script para actualizar todos los modelos con funcionalidad de equipos dañados
 * Ejecutar desde la raíz del proyecto: php update_all_models.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Lista de modelos a actualizar
$modelos = [
    'VideoVigilancia' => 'videovigilancia',
    'Telefonia' => 'telefonia', 
    'Sonido' => 'sonido',
    'Ram' => 'ram',
    'Procesador' => 'procesador',
    'Pila' => 'pila',
    'Monitor' => 'monitor',
    'Impresora' => 'impresora',
    'Conectividad' => 'conectividad',
    'Bateria' => 'bateria',
    'Almacenamiento' => 'almacenamiento',
    'Adaptador' => 'adaptador',
    'Microfono' => 'microfono'
];

foreach ($modelos as $className => $tableName) {
    $filePath = __DIR__ . "/frontend/models/{$className}.php";
    if (file_exists($filePath)) {
        echo "Actualizando modelo: $className\n";
        updateModel($filePath, $className);
    } else {
        echo "Archivo no encontrado: $filePath\n";
    }
}

function updateModel($filePath, $className) {
    $content = file_get_contents($filePath);
    
    // 1. Agregar estado BAJA si no existe
    if (!strpos($content, "'BAJA'")) {
        // Buscar el método getEstados y agregar BAJA
        $pattern = '/(public static function getEstados\(\)\s*\{[^}]+)(\s*\];\s*\})/';
        if (preg_match($pattern, $content)) {
            $content = preg_replace(
                $pattern,
                '$1' . "\n            'BAJA' => 'BAJA'," . '$2',
                $content
            );
            echo "  ✓ Estado BAJA agregado\n";
        }
    }
    
    // 2. Agregar métodos para equipos dañados si no existen
    if (!strpos($content, 'getEquiposDanados')) {
        $newMethods = '
    /**
     * Obtener equipos con estado dañado (proceso de baja)
     */
    public static function getEquiposDanados()
    {
        return self::find()->where([\'Estado\' => \'dañado(Proceso de baja)\'])->all();
    }

    /**
     * Contar equipos con estado dañado (proceso de baja)
     */
    public static function countEquiposDanados()
    {
        return self::find()->where([\'Estado\' => \'dañado(Proceso de baja)\'])->count();
    }';
        
        // Insertar antes del último }
        $lastBracePos = strrpos($content, '}');
        $content = substr_replace($content, $newMethods . "\n", $lastBracePos, 0);
        echo "  ✓ Métodos de equipos dañados agregados\n";
    }
    
    // 3. Actualizar validaciones para usar array_keys(self::getEstados())
    $content = preg_replace(
        '/\[\[\'Estado\'\], \'in\', \'range\' => \[([^\]]+)\]\]/',
        '[\'Estado\'], \'in\', \'range\' => array_keys(self::getEstados())]',
        $content
    );
    
    file_put_contents($filePath, $content);
    echo "  ✓ Modelo $className actualizado\n\n";
}

echo "Actualización de modelos completada!\n";
?>
