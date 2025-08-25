<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $baterias */
/** @var string|null $error */

$this->title = 'Listar Baterías';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0"><i class="fas fa-battery-three-quarters me-2"></i>Lista de Baterías</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> <?= Html::encode($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($baterias) && !$error): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay baterías registradas en el sistema.
                        </div>
                    <?php else: ?>
                        <!-- Botón para gestionar equipos dañados -->
                        <?php 
                            $equiposDanados = \frontend\models\Bateria::getEquiposDanados();
                            $cantidadDanados = count($equiposDanados);
                        ?>
                        <?php if ($cantidadDanados > 0): ?>
                            <div class="alert alert-warning d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Atención:</strong> Hay <?= $cantidadDanados ?> batería(s) en estado dañado que requieren gestión.
                                </div>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalGestionDanados">
                                    <i class="fas fa-tools me-2"></i>Gestionar Equipos Dañados
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar baterías...">
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="bateriasTable">
                                <thead class="table-warning">
                                    <tr>
                                        <th>ID</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Tipo</th>
                                        <th>Voltaje</th>
                                        <th>Capacidad</th>
                                        <th>Estado</th>
                                        <th>Ubicación</th>
                                        <th><i class="fas fa-clock me-1"></i>Tiempo Activo</th>
                                        <th><i class="fas fa-user me-1"></i>Último Editor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($baterias as $bateria): ?>
                                        <tr>
                                            <td><?= Html::encode($bateria->idBateria) ?></td>
                                            <td><?= Html::encode($bateria->MARCA) ?></td>
                                            <td><?= Html::encode($bateria->MODELO) ?></td>
                                            <td><?= Html::encode($bateria->TIPO) ?></td>
                                            <td><?= Html::encode($bateria->VOLTAJE) ?></td>
                                            <td><?= Html::encode($bateria->CAPACIDAD ?? '-') ?></td>
                                            <td>
                                                <span class="badge bg-<?= $bateria->ESTADO === 'Activo' ? 'success' : ($bateria->ESTADO === 'Inactivo' ? 'secondary' : 'danger') ?>">
                                                    <?= Html::encode($bateria->ESTADO) ?>
                                                </span>
                                            </td>
                                            <td><?= Html::encode($bateria->ubicacion_edificio) ?></td>
                                            <td>
                                                <span class="text-success fw-bold">
                                                    <i class="fas fa-hourglass-half me-1"></i>
                                                    <?= $bateria->getTiempoActivo() ?>
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-plus me-1"></i>
                                                    <?= $bateria->getFechaCreacionFormateada() ?: 'No disponible' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="text-primary fw-bold">
                                                    <i class="fas fa-user-edit me-1"></i>
                                                    <?= Html::encode($bateria->getInfoUltimoEditor()) ?>
                                                </span>
                                                <br>
                                                <small class="text-info">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?= $bateria->getTiempoUltimaEdicion() ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?= Html::a('<i class="fas fa-edit"></i>', 
                                                        ['site/bateria-editar', 'id' => $bateria->idBateria], 
                                                        ['class' => 'btn btn-sm btn-warning me-1', 'title' => 'Editar']) ?>
                                                    <?= Html::a('<i class="fas fa-eye"></i>', 
                                                        '#', 
                                                        ['class' => 'btn btn-sm btn-info', 'title' => 'Ver detalles']) ?>
                                                </div>
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

<!-- Modal para Gestión de Equipos Dañados -->
<div class="modal fade" id="modalGestionDanados" tabindex="-1" aria-labelledby="modalGestionDanadosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalGestionDanadosLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Gestión de Baterías Dañadas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($equiposDanados)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instrucciones:</strong> Selecciona las baterías que deseas cambiar a estado "BAJA" (dado de baja definitivo).
                    </div>
                    
                    <form id="formGestionDanados" method="post" action="<?= Url::to(['site/cambiar-estado-masivo']) ?>">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <?= Html::hiddenInput('modelo', 'Bateria') ?>
                        <?= Html::hiddenInput('nuevoEstado', 'BAJA') ?>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>ID</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Tipo</th>
                                        <th>Estado Actual</th>
                                        <th>Ubicación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($equiposDanados as $equipo): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="equipos[]" value="<?= $equipo->idBateria ?>" class="form-check-input equipo-checkbox">
                                            </td>
                                            <td><?= Html::encode($equipo->idBateria) ?></td>
                                            <td><?= Html::encode($equipo->MARCA) ?></td>
                                            <td><?= Html::encode($equipo->MODELO) ?></td>
                                            <td><?= Html::encode($equipo->TIPO) ?></td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    <?= Html::encode($equipo->ESTADO) ?>
                                                </span>
                                            </td>
                                            <td><?= Html::encode($equipo->ubicacion_edificio ?: 'No especificada') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        No hay baterías en estado dañado en este momento.
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <?php if (!empty($equiposDanados)): ?>
                    <button type="button" class="btn btn-danger" onclick="confirmarCambioEstado()">
                        <i class="fas fa-trash me-2"></i>Cambiar a Estado BAJA
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Funcionalidad de gestión de equipos dañados
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.equipo-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

function confirmarCambioEstado() {
    const checkboxes = document.querySelectorAll('.equipo-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Por favor selecciona al menos una batería.');
        return;
    }
    
    const cantidad = checkboxes.length;
    const mensaje = `¿Estás seguro de que deseas cambiar ${cantidad} batería(s) al estado BAJA?\n\nEsta acción marcará los equipos como dados de baja definitivamente.`;
    
    if (confirm(mensaje)) {
        document.getElementById('formGestionDanados').submit();
    }
}

// Funcionalidad de búsqueda
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('bateriasTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    }
});
</script>
