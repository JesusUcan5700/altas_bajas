<?php
/**
 * Script para actualizar múltiples modelos con estado BAJA y métodos de equipos dañados
 */

// Modelos a actualizar con sus campos de estado específicos
$modelos = [
    'Impresora' => 'Estado',
    'Conectividad' => 'Estado', 
    'Ram' => 'Estado',
    'Procesador' => 'Estado',
    'Almacenamiento' => 'Estado',
    'Adaptador' => 'Estado'
];

foreach ($modelos as $modelo => $campoEstado) {
    $archivo = __DIR__ . "/frontend/models/{$modelo}.php";
    
    if (file_exists($archivo)) {
        echo "Actualizando modelo: $modelo\n";
        actualizarModelo($archivo, $modelo, $campoEstado);
    } else {
        echo "❌ No encontrado: $archivo\n";
    }
}

function actualizarModelo($archivo, $modelo, $campoEstado) {
    $contenido = file_get_contents($archivo);
    
    // 1. Agregar constante ESTADO_BAJA si no existe
    if (strpos($contenido, 'ESTADO_BAJA') === false) {
        $patron = '/(const ESTADO_MANTENIMIENTO = [^;]+;)/';
        if (preg_match($patron, $contenido)) {
            $contenido = preg_replace(
                $patron,
                '$1' . "\n    const ESTADO_BAJA = 'BAJA';",
                $contenido
            );
            echo "  ✅ Constante ESTADO_BAJA agregada\n";
        }
    }
    
    // 2. Actualizar método getEstados para incluir BAJA
    if (strpos($contenido, 'self::ESTADO_BAJA') === false && strpos($contenido, "'BAJA' => 'BAJA'") === false) {
        $patron = '/(public static function getEstados\(\)[^{]*\{[^}]+)(],?\s*);(\s*})/s';
        if (preg_match($patron, $contenido)) {
            $contenido = preg_replace(
                $patron,
                '$1' . ",\n            'BAJA' => 'BAJA'" . '$2;$3',
                $contenido
            );
            echo "  ✅ Estado BAJA agregado a getEstados()\n";
        }
    }
    
    // 3. Agregar métodos de equipos dañados si no existen
    if (strpos($contenido, 'getEquiposDanados') === false) {
        $metodosNuevos = "
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
        $ultimaLlave = strrpos($contenido, '}');
        $contenido = substr_replace($contenido, $metodosNuevos . "\n", $ultimaLlave, 0);
        echo "  ✅ Métodos de equipos dañados agregados\n";
    }
    
    // 4. Actualizar validaciones para usar array_keys
    $contenido = preg_replace(
        '/\[\[\'(' . preg_quote($campoEstado) . ')\'\], \'in\', \'range\' => \[[^\]]+\]\]/',
        '[[\'$1\'], \'in\', \'range\' => array_keys(self::getEstados())]',
        $contenido
    );
    
    file_put_contents($archivo, $contenido);
    echo "  ✅ Modelo $modelo actualizado exitosamente\n\n";
}

echo "¡Actualización de modelos completada!\n";
?>
