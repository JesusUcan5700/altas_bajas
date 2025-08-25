<?php
/**
 * Script para automatizar la actualización de todos los modelos restantes
 */

// Lista de modelos restantes a actualizar
$modelosRestantes = [
    'Ram' => 'Estado',
    'Procesador' => 'Estado', 
    'Monitor' => 'Estado',
    'Impresora' => 'Estado',
    'Conectividad' => 'Estado',
    'Bateria' => 'Estado',
    'Almacenamiento' => 'Estado',
    'Adaptador' => 'Estado'
];

foreach ($modelosRestantes as $modelo => $campoEstado) {
    $rutaArchivo = __DIR__ . "/frontend/models/{$modelo}.php";
    
    if (file_exists($rutaArchivo)) {
        echo "Actualizando modelo: $modelo\n";
        actualizarModelo($rutaArchivo, $modelo, $campoEstado);
    } else {
        echo "Archivo no encontrado: $rutaArchivo\n";
    }
}

function actualizarModelo($rutaArchivo, $modelo, $campoEstado) {
    $contenido = file_get_contents($rutaArchivo);
    
    // 1. Buscar si ya existe getEstados
    if (strpos($contenido, 'function getEstados') !== false) {
        // Agregar BAJA al array de estados existente
        $patron = '/(public static function getEstados\(\)[^{]*\{[^}]*\],?)\s*(\];)/';
        if (preg_match($patron, $contenido)) {
            $contenido = preg_replace(
                $patron,
                '$1' . "\n            'BAJA' => 'BAJA'," . "\n        $2",
                $contenido
            );
        }
    } else {
        // Crear método getEstados completo
        $metodoEstados = "
    /**
     * Obtiene los estados disponibles estandarizados
     */
    public static function getEstados()
    {
        return [
            'Activo' => 'Activo',
            'Inactivo(Sin Asignar)' => 'Inactivo(Sin Asignar)',
            'dañado(Proceso de baja)' => 'dañado(Proceso de baja)',
            'En Mantenimiento' => 'En Mantenimiento',
            'BAJA' => 'BAJA',
        ];
    }";
        
        // Insertar antes del último }
        $posicionUltimaLlave = strrpos($contenido, '}');
        $contenido = substr_replace($contenido, $metodoEstados . "\n", $posicionUltimaLlave, 0);
    }
    
    // 2. Agregar métodos para equipos dañados
    if (strpos($contenido, 'getEquiposDanados') === false) {
        $metodosEquiposDanados = "
    /**
     * Obtener equipos con estado dañado (proceso de baja)
     */
    public static function getEquiposDanados()
    {
        return self::find()->where(['$campoEstado' => 'dañado(Proceso de baja)'])->all();
    }

    /**
     * Contar equipos con estado dañado (proceso de baja)
     */
    public static function countEquiposDanados()
    {
        return self::find()->where(['$campoEstado' => 'dañado(Proceso de baja)'])->count();
    }";
        
        // Insertar antes del último }
        $posicionUltimaLlave = strrpos($contenido, '}');
        $contenido = substr_replace($contenido, $metodosEquiposDanados . "\n", $posicionUltimaLlave, 0);
    }
    
    // 3. Actualizar validaciones si existen
    $contenido = preg_replace(
        '/\[\[\'(' . $campoEstado . ')\'\], \'in\', \'range\' => \[[^\]]+\]\]/',
        '[[\'$1\'], \'in\', \'range\' => array_keys(self::getEstados())]',
        $contenido
    );
    
    file_put_contents($rutaArchivo, $contenido);
    echo "  ✓ Modelo $modelo actualizado exitosamente\n";
}

echo "\n¡Actualización de modelos completada!\n";
?>
