<?php
/**
 * Generador de código para aplicar a todas las vistas de listado restantes
 */

// Lista de vistas pendientes con sus respectivos datos
$vistas = [
    'sonido' => ['modelo' => 'Sonido', 'id_campo' => 'idSONIDO'],
    'equipo' => ['modelo' => 'Equipo', 'id_campo' => 'idEQUIPO'],
    'bateria' => ['modelo' => 'Bateria', 'id_campo' => 'idBATERIA'],
    'conectividad' => ['modelo' => 'Conectividad', 'id_campo' => 'idCONECTIVIDAD'],
    'impresora' => ['modelo' => 'Impresora', 'id_campo' => 'idIMPRESORA'],
    'monitor' => ['modelo' => 'Monitor', 'id_campo' => 'idMONITOR'],
    'procesador' => ['modelo' => 'Procesador', 'id_campo' => 'idPROCESADOR'],
    'ram' => ['modelo' => 'Ram', 'id_campo' => 'idRAM'],
    'almacenamiento' => ['modelo' => 'Almacenamiento', 'id_campo' => 'idALMACENAMIENTO'],
    'adaptador' => ['modelo' => 'Adaptador', 'id_campo' => 'idADAPTADOR']
];

echo "=== CÓDIGO PARA APLICAR A TODAS LAS VISTAS ===\n\n";

echo "1. RECUADRO DE EQUIPOS DAÑADOS (insertar después del mensaje de error):\n";
echo str_repeat("=", 80) . "\n";

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

echo $codigoRecuadro . "\n\n";

echo "2. MODAL COMPLETO (insertar antes del script final):\n";
echo str_repeat("=", 80) . "\n";

$codigoModal = '
<!-- Modal de Equipos Dañados -->
<?php if ($countDanados > 0): ?>
<div class="modal fade" id="modalEquiposDanados" tabindex="-1" aria-labelledby="modalEquiposDanadosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEquiposDanadosLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Equipos en Proceso de Baja (<?= $countDanados ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Seleccione los equipos que desea cambiar a estado <strong>"BAJA"</strong> y haga clic en el botón correspondiente.
                </div>
                
                <form id="formCambiarEstado" method="post" action="<?= \\yii\\helpers\\Url::to([\'site/cambiar-estado-masivo\']) ?>">
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    <input type="hidden" name="modelo" value="{MODELO}">
                    <input type="hidden" name="nuevo_estado" value="BAJA">
                    
                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="seleccionarTodos()">
                            <i class="fas fa-check-square me-1"></i>Seleccionar Todos
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="deseleccionarTodos()">
                            <i class="fas fa-square me-1"></i>Deseleccionar Todos
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkTodos" onchange="toggleTodos()"></th>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>N° Serie</th>
                                    <th>N° Inventario</th>
                                    <th>Ubicación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($equiposDanados as $equipo): ?>
                                <tr>
                                    <td><input type="checkbox" name="equipos[]" value="<?= $equipo->{ID_CAMPO} ?>" class="check-equipo"></td>
                                    <td><?= htmlspecialchars($equipo->{ID_CAMPO}) ?></td>
                                    <td><?= htmlspecialchars($equipo->MARCA) ?></td>
                                    <td><?= htmlspecialchars($equipo->MODELO) ?></td>
                                    <td><?= htmlspecialchars($equipo->NUMERO_SERIE) ?></td>
                                    <td><?= htmlspecialchars($equipo->NUMERO_INVENTARIO) ?></td>
                                    <td><?= htmlspecialchars($equipo->ubicacion_edificio) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger" onclick="return confirmarCambioEstado()">
                            <i class="fas fa-arrow-down me-2"></i>Cambiar a BAJA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>';

echo $codigoModal . "\n\n";

echo "3. JAVASCRIPT A AGREGAR AL SCRIPT EXISTENTE:\n";
echo str_repeat("=", 80) . "\n";

$codigoJS = '
// Funciones para el modal de equipos dañados
function toggleTodos() {
    const checkTodos = document.getElementById(\'checkTodos\');
    const checks = document.querySelectorAll(\'.check-equipo\');
    checks.forEach(check => check.checked = checkTodos.checked);
}

function seleccionarTodos() {
    const checks = document.querySelectorAll(\'.check-equipo\');
    checks.forEach(check => check.checked = true);
    document.getElementById(\'checkTodos\').checked = true;
}

function deseleccionarTodos() {
    const checks = document.querySelectorAll(\'.check-equipo\');
    checks.forEach(check => check.checked = false);
    document.getElementById(\'checkTodos\').checked = false;
}

function confirmarCambioEstado() {
    const checks = document.querySelectorAll(\'.check-equipo:checked\');
    if (checks.length === 0) {
        alert(\'⚠️ Por favor seleccione al menos un equipo para cambiar el estado.\');
        return false;
    }
    
    return confirm(`¿Está seguro que desea cambiar el estado de ${checks.length} equipo(s) a \'BAJA\'?\\n\\nEsta acción no se puede deshacer.`);
}';

echo $codigoJS . "\n\n";

echo "4. REEMPLAZOS POR VISTA:\n";
echo str_repeat("=", 80) . "\n";

foreach ($vistas as $vista => $datos) {
    echo "VISTA: {$vista}\n";
    echo "  - Reemplazar {MODELO} por: " . $datos['modelo'] . "\n";
    echo "  - Reemplazar {ID_CAMPO} por: " . $datos['id_campo'] . "\n";
    echo "  - Archivo: frontend/views/site/{$vista}/listar.php\n\n";
}

echo "¡Usa estos códigos para aplicar la funcionalidad a todas las vistas!\n";
?>
