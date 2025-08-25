<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $camaras */
/** @var string|null $error */

$this->title = 'Listar Video Vigilancia';

// Registrar Font Awesome CDN
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0"><i class="fas fa-video me-2"></i>Lista de Equipos de Video Vigilancia</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> <?= Html::encode($error) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Recuadro de Equipos Dañados -->
                    <?php 
                    $equiposDanados = \frontend\models\VideoVigilancia::getEquiposDanados();
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
                    <?php endif; ?>

                    <?php if (empty($camaras) && !$error): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay equipos de video vigilancia registrados en el sistema. Por favor, agregue algunos equipos para comenzar.
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar equipos de video vigilancia...">
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="camarasTable">
                                <thead class="table-danger">
                                    <tr>
                                        <th>ID</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Tipo de Cámara</th>
                                        <th>Estado</th>
                                        <th>Ubicación</th>
                                        <th><i class="fas fa-clock text-info"></i> Tiempo Activo</th>
                                        <th><i class="fas fa-user-edit text-warning"></i> Último Editor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($camaras as $camara): ?>
                                        <tr>
                                            <td><?= Html::encode($camara->idVIDEO_VIGILANCIA) ?></td>
                                            <td><?= Html::encode($camara->MARCA ?? '-') ?></td>
                                            <td><?= Html::encode($camara->MODELO ?? '-') ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= Html::encode(ucfirst($camara->tipo_camara ?? 'fija')) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $camara->ESTADO === 'activo' ? 'success' : ($camara->ESTADO === 'inactivo' ? 'secondary' : 'danger') ?>">
                                                    <?= Html::encode($camara->ESTADO ?? '-') ?>
                                                </span>
                                            </td>
                                            <td><?= Html::encode($camara->ubicacion_edificio ?? '-') ?></td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock text-info"></i>
                                                    <?= $camara->getTiempoActivo() ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-user text-warning"></i>
                                                    <?= Html::encode($camara->getInfoUltimoEditor()) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?= Html::a('<i class="fas fa-edit"></i>', 
                                                    ['site/videovigilancia-editar', 'id' => $camara->idVIDEO_VIGILANCIA], 
                                                    ['class' => 'btn btn-sm btn-danger', 'title' => 'Editar']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <div class="mt-3">
                        <?= Html::a('<i class="fas fa-arrow-left me-2"></i>Volver a Gestión', 
                            ['site/gestion-categorias'], 
                            ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                
                <form id="formCambiarEstado" method="post" action="<?= \yii\helpers\Url::to(['site/cambiar-estado-masivo']) ?>">
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    <input type="hidden" name="modelo" value="VideoVigilancia">
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
                                    <th>
                                        <input type="checkbox" id="checkTodos" onchange="toggleTodos()">
                                    </th>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Tipo</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($equiposDanados as $equipo): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="equipos[]" value="<?= $equipo->idVIDEO_VIGILANCIA ?>" class="check-equipo">
                                    </td>
                                    <td><?= htmlspecialchars($equipo->idVIDEO_VIGILANCIA) ?></td>
                                    <td><?= htmlspecialchars($equipo->MARCA) ?></td>
                                    <td><?= htmlspecialchars($equipo->MODELO) ?></td>
                                    <td><?= htmlspecialchars($equipo->tipo_camara ?? 'N/A') ?></td>
                                    <td>
                                        <?= htmlspecialchars($equipo->ubicacion_edificio) ?>
                                        <?php if ($equipo->ubicacion_detalle): ?>
                                            - <?= htmlspecialchars($equipo->ubicacion_detalle) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning"><?= htmlspecialchars($equipo->ESTADO) ?></span>
                                    </td>
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
<?php endif; ?>

<script>
// Funcionalidad de búsqueda
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('camarasTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    }
});

// Funciones para el modal de equipos dañados
function toggleTodos() {
    const checkTodos = document.getElementById('checkTodos');
    const checks = document.querySelectorAll('.check-equipo');
    checks.forEach(check => check.checked = checkTodos.checked);
}

function seleccionarTodos() {
    const checks = document.querySelectorAll('.check-equipo');
    checks.forEach(check => check.checked = true);
    document.getElementById('checkTodos').checked = true;
}

function deseleccionarTodos() {
    const checks = document.querySelectorAll('.check-equipo');
    checks.forEach(check => check.checked = false);
    document.getElementById('checkTodos').checked = false;
}

function confirmarCambioEstado() {
    const checks = document.querySelectorAll('.check-equipo:checked');
    if (checks.length === 0) {
        alert('⚠️ Por favor seleccione al menos un equipo para cambiar el estado.');
        return false;
    }
    
    return confirm(`¿Está seguro que desea cambiar el estado de ${checks.length} equipo(s) a 'BAJA'?\\n\\nEsta acción no se puede deshacer.`);
}
</script>
