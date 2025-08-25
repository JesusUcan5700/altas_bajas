<?php
/**
 * Script para agregar funcionalidad de equipos dañados a todas las vistas de listado
 */

// Mapeo de vistas y sus modelos correspondientes
$vistas = [
    'telefonia' => 'Telefonia',
    'sonido' => 'Sonido', 
    'equipo' => 'Equipo',
    'bateria' => 'Bateria',
    'conectividad' => 'Conectividad',
    'impresora' => 'Impresora',
    'monitor' => 'Monitor',
    'procesador' => 'Procesador',
    'ram' => 'Ram',
    'almacenamiento' => 'Almacenamiento',
    'adaptador' => 'Adaptador'
];

// Código del recuadro de equipos dañados
$codigoRecuadro = '
                    <!-- Recuadro de Equipos Dañados -->
                    <?php 
                    $equiposDanados = \\frontend\\models\\{MODELO}::getEquiposDanados();
                    $countDanados = count($equiposDanados);
                    ?>
                    <?php if ($countDanados > 0): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-warning border-warning">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="alert-heading mb-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Equipos en Proceso de Baja
                                        </h5>
                                        <p class="mb-0">
                                            Hay <strong><?= $countDanados ?></strong> equipo(s) con estado "dañado(Proceso de baja)" que requieren atención.
                                        </p>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEquiposDanados">
                                            <i class="fas fa-eye me-2"></i>Ver Detalles
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>';

foreach ($vistas as $vista => $modelo) {
    $rutaVista = __DIR__ . "/frontend/views/site/{$vista}/listar.php";
    
    if (file_exists($rutaVista)) {
        echo "Actualizando vista: $vista\n";
        actualizarVista($rutaVista, $modelo, $codigoRecuadro);
    } else {
        echo "Vista no encontrada: $rutaVista\n";
    }
}

function actualizarVista($rutaVista, $modelo, $codigoRecuadro) {
    $contenido = file_get_contents($rutaVista);
    
    // Reemplazar {MODELO} con el nombre real del modelo
    $codigoRecuadroReal = str_replace('{MODELO}', $modelo, $codigoRecuadro);
    
    // Buscar un lugar apropiado para insertar el recuadro
    // Intentamos insertar después de mensajes de error o al inicio del contenido
    $patrones = [
        '/(\?\>\s*\<\?php endif; \?\>\s*\n)(.*?<div class="mb-3">|.*?<div class="table-responsive">)/s',
        '/(<div class="card-body">\s*)(.*?)(<div class="mb-3">|<div class="table-responsive">)/s'
    ];
    
    $insertado = false;
    foreach ($patrones as $patron) {
        if (preg_match($patron, $contenido)) {
            $contenido = preg_replace($patron, '$1' . $codigoRecuadroReal . '$2$3', $contenido, 1);
            $insertado = true;
            break;
        }
    }
    
    if (!$insertado) {
        echo "  ⚠️  No se pudo insertar automáticamente el recuadro\n";
        return;
    }
    
    file_put_contents($rutaVista, $contenido);
    echo "  ✓ Vista $vista actualizada\n";
}

echo "\n¡Actualización de vistas completada!\n";
?>
