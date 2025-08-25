<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $monitores */
/** @var string|null $error */

$this->title = 'Gestión de Monitores';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Agregar estilos
$this->registerCss("
    .equipment-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }
    
    .btn-equipment {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
    }
    
    .equipment-card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 15px;
    }
");
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card equipment-card">
                <div class="card-header equipment-header text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-tv me-2"></i>Gestión de Monitores
                    </h3>
                    <p class="mb-0 mt-2">Pantallas y Displays</p>
                </div>
                <div class="card-body">
                    <!-- Barra de herramientas -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-muted">
                                <i class="fas fa-list me-2"></i>Equipos Registrados
                            </h5>
                            <?php if ($error): ?>
                                <div class="alert alert-danger">
                                    <strong>❌ ERROR:</strong> <?= htmlspecialchars($error) ?>
                                </div>
                            <?php elseif (empty($monitores)): ?>
                                <div class="alert alert-warning">
                                    <strong>📭 SIN EQUIPOS:</strong> No hay monitores registrados.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    <strong>✅ DATOS CARGADOS:</strong> <?= count($monitores) ?> equipos encontrados
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="btn btn-secondary btn-equipment">
                                <i class="fas fa-arrow-left me-2"></i>Volver al Menú
                            </a>
                        </div>
                    </div>

                    <!-- Recuadro de Equipos Dañados -->
                    <?php 
                    $equiposDanados = \frontend\models\Monitor::getEquiposDanados();
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

                    <!-- Buscador -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="buscar_monitor" placeholder="Buscar por marca, modelo, resolución, tipo pantalla...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Monitores -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Tamaño</th>
                                    <th>Resolución</th>
                                    <th>Tipo Pantalla</th>
                                    <th>Frecuencia</th>
                                    <th>N° Serie</th>
                                    <th>Estado</th>
                                    <th>Tiempo Activo</th>
                                    <th>Último Editor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_monitores">
                                <?php if (empty($monitores) && !$error): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> No hay monitores registrados en el sistema. Por favor, agregue algunos equipos para comenzar.
                                        </td>
                                    </tr>
                                <?php elseif ($error): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos: <?= Html::encode($error) ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($monitores as $monitor): ?>
                                        <tr>
                                            <td><strong><?= Html::encode($monitor->idMonitor) ?></strong></td>
                                            <td><?= Html::encode($monitor->MARCA ?? '-') ?></td>
                                            <td><?= Html::encode($monitor->MODELO ?? '-') ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= Html::encode($monitor->TAMANIO ?? '-') ?></span>
                                            </td>
                                            <td>
                                                <small class="text-primary fw-bold"><?= Html::encode($monitor->RESOLUCION ?? '-') ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?= Html::encode($monitor->TIPO_PANTALLA ?? '-') ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark"><?= Html::encode($monitor->FRECUENCIA_HZ ?? '-') ?></span>
                                            </td>
                                            <td>
                                                <small><?= Html::encode($monitor->NUMERO_SERIE ?? '-') ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $estado = strtolower($monitor->ESTADO ?? '');
                                                $badgeClass = match($estado) {
                                                    'activo' => 'bg-success',
                                                    'reparación', 'reparacion' => 'bg-warning',
                                                    'inactivo', 'dañado', 'danado' => 'bg-secondary',
                                                    'baja' => 'bg-danger',
                                                    default => 'bg-dark'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= Html::encode($monitor->ESTADO ?? '-') ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= $monitor->getAnosActivoTexto() ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= $monitor->getInfoUltimaEdicion() ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?= Html::a('<i class="fas fa-edit"></i>', 
                                                        ['site/monitor-editar', 'id' => $monitor->idMonitor], 
                                                        ['class' => 'btn btn-sm btn-success', 'title' => 'Editar']) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
// Datos de Monitores
let monitoresData = " . json_encode($monitores, JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) . ";

// Función de búsqueda
document.getElementById('buscar_monitor').addEventListener('input', function() {
    const filtro = this.value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbody_monitores tr');
    
    filas.forEach(fila => {
        if (fila.cells && fila.cells.length >= 10) {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = filtro === '' || texto.includes(filtro) ? '' : 'none';
        }
    });
});

// Función para ver detalles
function verDetalles(id) {
    const monitor = monitoresData.find(m => m.idMonitor == id);
    if (monitor) {
        alert('📋 Detalles del Monitor\\n\\n' +
              '🆔 ID: ' + (monitor.idMonitor || 'N/A') + '\\n' +
              '🏷️ Marca: ' + (monitor.MARCA || 'N/A') + '\\n' +
              '📱 Modelo: ' + (monitor.MODELO || 'N/A') + '\\n' +
              '📺 Tamaño: ' + (monitor.TAMANIO || 'N/A') + '\\n' +
              '🖥️ Resolución: ' + (monitor.RESOLUCION || 'N/A') + '\\n' +
              '🎨 Tipo Pantalla: ' + (monitor.TIPO_PANTALLA || 'N/A') + '\\n' +
              '⚡ Frecuencia: ' + (monitor.FRECUENCIA_HZ || 'N/A') + '\\n' +
              '🔌 Entradas: ' + (monitor.ENTRADAS_VIDEO || 'N/A') + '\\n' +
              '🔢 Serie: ' + (monitor.NUMERO_SERIE || 'N/A') + '\\n' +
              '📦 Inventario: ' + (monitor.NUMERO_INVENTARIO || 'N/A') + '\\n' +
              '🔄 Estado: ' + (monitor.ESTADO || 'N/A') + '\\n' +
              '🏢 Ubicación: ' + (monitor.ubicacion_edificio || 'N/A') + '\\n' +
              '📍 Detalle: ' + (monitor.ubicacion_detalle || 'N/A') + '\\n' +
              '📅 Emisión: ' + (monitor.EMISION_INVENTARIO || 'N/A') + '\\n' +
              '📝 Descripción: ' + (monitor.DESCRIPCION || 'N/A'));
    }
}

console.log('✅ Sistema de Monitores cargado con', monitoresData.length, 'equipos');
");
?>

<!-- Modal para Equipos Dañados -->
<div class="modal fade" id="modalEquiposDanados" tabindex="-1" aria-labelledby="modalEquiposDanadosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEquiposDanadosLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Monitores en Proceso de Baja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($countDanados > 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Selecciona los monitores que deseas cambiar de estado:
                </div>

                <?= \yii\helpers\Html::beginForm(['site/cambiar-estado-masivo'], 'post', [
                    'id' => 'formCambioMasivo',
                    'data-csrf' => Yii::$app->request->csrfToken
                ]) ?>
                
                <?= \yii\helpers\Html::hiddenInput('modelo', 'Monitor') ?>
                <?= \yii\helpers\Html::hiddenInput('nuevoEstado', 'BAJA') ?>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Los monitores seleccionados cambiarán automáticamente al estado <strong>"BAJA"</strong>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="seleccionarTodos">
                            <label class="form-check-label" for="seleccionarTodos">
                                Seleccionar Todos
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="50">
                                    <i class="fas fa-check-square"></i>
                                </th>
                                <th>ID</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Tamaño</th>
                                <th>Resolución</th>
                                <th>Nº Serie</th>
                                <th>Nº Inventario</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equiposDanados as $monitor): ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input equipo-checkbox" type="checkbox" 
                                               name="equipos[]" value="<?= $monitor->idMonitor ?>" 
                                               id="equipo_<?= $monitor->idMonitor ?>">
                                    </div>
                                </td>
                                <td><?= \yii\helpers\Html::encode($monitor->idMonitor) ?></td>
                                <td><?= \yii\helpers\Html::encode($monitor->MARCA) ?></td>
                                <td><?= \yii\helpers\Html::encode($monitor->MODELO) ?></td>
                                <td><?= \yii\helpers\Html::encode($monitor->TAMANIO) ?></td>
                                <td><?= \yii\helpers\Html::encode($monitor->RESOLUCION) ?></td>
                                <td><?= \yii\helpers\Html::encode($monitor->NUMERO_SERIE) ?></td>
                                <td><?= \yii\helpers\Html::encode($monitor->NUMERO_INVENTARIO) ?></td>
                                <td><?= \yii\helpers\Html::encode($monitor->ubicacion_edificio) ?></td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <?= \yii\helpers\Html::encode($monitor->ESTADO) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    No hay monitores en proceso de baja.
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <?php if ($countDanados > 0): ?>
                <button type="submit" class="btn btn-warning" id="btnCambiarEstado">
                    <i class="fas fa-exchange-alt me-2"></i>Cambiar Estado
                </button>
                <?php endif; ?>
                <?= \yii\helpers\Html::endForm() ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar selección de todos los checkboxes
    const seleccionarTodos = document.getElementById('seleccionarTodos');
    const checkboxes = document.querySelectorAll('.equipo-checkbox');
    
    if (seleccionarTodos) {
        seleccionarTodos.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Manejar envío del formulario
    const form = document.getElementById('formCambioMasivo');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const equiposSeleccionados = document.querySelectorAll('.equipo-checkbox:checked');
            
            if (equiposSeleccionados.length === 0) {
                alert('⚠️ Debes seleccionar al menos un monitor.');
                return;
            }
            
            if (confirm(`¿Estás seguro de cambiar ${equiposSeleccionados.length} monitor(es) al estado "BAJA"?`)) {
                // Deshabilitar el botón para evitar doble envío
                const btnCambiar = document.getElementById('btnCambiarEstado');
                if (btnCambiar) {
                    btnCambiar.disabled = true;
                    btnCambiar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
                }
                
                this.submit();
            }
        });
    }
});
</script>
