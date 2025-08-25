<?php

/** @var yii\web\View $this */
/* @var $adaptadores array */
/* @var $error string|null */

$this->title = 'Gestión de Adaptadores';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Agregar estilos
$this->registerCss("
    .equipment-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
                        <i class="fas fa-plug me-2"></i>Gestión de Adaptadores
                    </h3>
                    <p class="mb-0 mt-2">Adaptadores de Corriente y Cargadores</p>
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
                            <?php elseif (empty($adaptadores)): ?>
                                <div class="alert alert-warning">
                                    <strong>📭 SIN EQUIPOS:</strong> No hay adaptadores registrados.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    <strong>✅ DATOS CARGADOS:</strong> <?= count($adaptadores) ?> equipos encontrados
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= \yii\helpers\Url::to(['site/gestion-categorias']) ?>" class="btn btn-secondary btn-equipment">
                                <i class="fas fa-arrow-left me-2"></i>Volver a Gestión
                            </a>
                        </div>
                    </div>

                    <!-- Recuadro de Equipos Dañados -->
                    <?php 
                    $equiposDanados = \frontend\models\Adaptador::getEquiposDanados();
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
                                            Hay <strong><?= $countDanados ?></strong> adaptador(es) con estado "dañado(Proceso de baja)" que requieren atención.
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
                                <input type="text" class="form-control" id="buscar_adaptador" placeholder="Buscar por marca, modelo, tipo...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Adaptadores -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Tipo</th>
                                    <th>Voltaje</th>
                                    <th>Amperaje</th>
                                    <th>Potencia</th>
                                    <th>N° Serie</th>
                                    <th>Estado</th>
                                    <th>Emisión</th>
                                    <th>Tiempo Activo</th>
                                    <th>Último Editor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_adaptador">
                                <?php if (empty($adaptadores) && !$error): ?>
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> No hay adaptadores registrados
                                        </td>
                                    </tr>
                                <?php elseif ($error): ?>
                                    <tr>
                                        <td colspan="13" class="text-center text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($adaptadores as $adaptador): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($adaptador->idAdaptador) ?></strong></td>
                                            <td><?= htmlspecialchars($adaptador->MARCA ?? '-') ?></td>
                                            <td><?= htmlspecialchars($adaptador->MODELO ?? '-') ?></td>
                                            <td><?= htmlspecialchars($adaptador->TIPO ?? '-') ?></td>
                                            <td><?= htmlspecialchars($adaptador->VOLTAJE ?? '-') ?></td>
                                            <td><?= htmlspecialchars($adaptador->AMPERAJE ?? '-') ?></td>
                                            <td><?= htmlspecialchars($adaptador->POTENCIA_WATTS ?? '-') ?></td>
                                            <td><?= htmlspecialchars($adaptador->NUMERO_SERIE ?? '-') ?></td>
                                            <td>
                                                <?php
                                                $estado = strtolower($adaptador->ESTADO ?? '');
                                                $badgeClass = match($estado) {
                                                    'activo' => 'bg-success',
                                                    'mantenimiento' => 'bg-warning',
                                                    'inactivo', 'dañado', 'danado' => 'bg-secondary',
                                                    'baja' => 'bg-danger',
                                                    default => 'bg-dark'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($adaptador->ESTADO ?? '-') ?></span>
                                            </td>
                                            <td><?= !empty($adaptador->EMISION_INVENTARIO) ? date('d/m/Y', strtotime($adaptador->EMISION_INVENTARIO)) : '-' ?></td>
                                            <td>
                                                <?php
                                                if (!empty($adaptador->EMISION_INVENTARIO)) {
                                                    try {
                                                        $fechaEmision = new DateTime($adaptador->EMISION_INVENTARIO);
                                                        $fechaActual = new DateTime();
                                                        $diferencia = $fechaActual->diff($fechaEmision);
                                                        $dias = $diferencia->days;
                                                        $anos = floor($dias / 365.25);
                                                        
                                                        if ($anos > 0) {
                                                            echo "<span class='text-primary'>{$anos} año" . ($anos > 1 ? 's' : '') . "</span>";
                                                        } else {
                                                            echo "<span class='text-info'>{$dias} día" . ($dias > 1 ? 's' : '') . "</span>";
                                                        }
                                                    } catch (Exception $e) {
                                                        echo '-';
                                                    }
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $editor = $adaptador->ultimo_editor ?? 'No especificado';
                                                if ($editor === 'Sistema') {
                                                    echo '<span class="badge bg-secondary">Sistema</span>';
                                                } else {
                                                    echo '<span class="badge bg-info">' . htmlspecialchars($editor) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-info" onclick="verDetalles(<?= $adaptador->idAdaptador ?>)" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="<?= \yii\helpers\Url::to(['site/adaptador-editar', 'id' => $adaptador->idAdaptador]) ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
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
$adaptadoresJson = json_encode(array_map(function($adaptador) {
    return $adaptador->attributes;
}, $adaptadores), JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);

$this->registerJs("
// Datos de Adaptadores
let adaptadorData = " . $adaptadoresJson . ";

// Función de búsqueda
document.getElementById('buscar_adaptador').addEventListener('input', function() {
    const filtro = this.value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbody_adaptador tr');
    
    filas.forEach(fila => {
        if (fila.cells && fila.cells.length >= 10) {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = filtro === '' || texto.includes(filtro) ? '' : 'none';
        }
    });
});

// Función para ver detalles
function verDetalles(id) {
    const adaptador = adaptadorData.find(a => a.idAdaptador == id);
    if (adaptador) {
        alert('📋 Detalles del Adaptador\\n\\n' +
              '🆔 ID: ' + (adaptador.idAdaptador || 'N/A') + '\\n' +
              '🏷️ Marca: ' + (adaptador.MARCA || 'N/A') + '\\n' +
              '📱 Modelo: ' + (adaptador.MODELO || 'N/A') + '\\n' +
              '🔌 Tipo: ' + (adaptador.TIPO || 'N/A') + '\\n' +
              '⚡ Voltaje: ' + (adaptador.VOLTAJE || 'N/A') + '\\n' +
              '🔋 Amperaje: ' + (adaptador.AMPERAJE || 'N/A') + '\\n' +
              '💡 Potencia: ' + (adaptador.POTENCIA_WATTS || 'N/A') + '\\n' +
              '🔢 Serie: ' + (adaptador.NUMERO_SERIE || 'N/A') + '\\n' +
              '🔄 Estado: ' + (adaptador.ESTADO || 'N/A') + '\\n' +
              '🏢 Ubicación: ' + (adaptador.ubicacion_edificio || 'N/A') + '\\n' +
              '� Detalle: ' + (adaptador.ubicacion_detalle || 'N/A') + '\\n' +
              '📅 Emisión: ' + (adaptador.EMISION_INVENTARIO || 'N/A') + '\\n' +
              '�📝 Descripción: ' + (adaptador.DESCRIPCION || 'N/A'));
    }
}

console.log('✅ Sistema de Adaptadores cargado con', adaptadorData.length, 'equipos');
");
?>

<!-- Modal para Equipos Dañados -->
<div class="modal fade" id="modalEquiposDanados" tabindex="-1" aria-labelledby="modalEquiposDanadosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEquiposDanadosLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Adaptadores en Proceso de Baja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($countDanados > 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Selecciona los adaptadores que deseas cambiar de estado:
                </div>

                <?= \yii\helpers\Html::beginForm(['site/cambiar-estado-masivo'], 'post', [
                    'id' => 'formCambioMasivo',
                    'data-csrf' => Yii::$app->request->csrfToken
                ]) ?>
                
                <?= \yii\helpers\Html::hiddenInput('modelo', 'Adaptador') ?>
                <?= \yii\helpers\Html::hiddenInput('nuevoEstado', 'BAJA') ?>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Los adaptadores seleccionados cambiarán automáticamente al estado <strong>"BAJA"</strong>
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
                                <th>Tipo</th>
                                <th>Voltaje</th>
                                <th>Amperaje</th>
                                <th>Potencia</th>
                                <th>Nº Serie</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equiposDanados as $adaptador): ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input equipo-checkbox" type="checkbox" 
                                               name="equipos[]" value="<?= $adaptador->idAdaptador ?>" 
                                               id="equipo_<?= $adaptador->idAdaptador ?>">
                                    </div>
                                </td>
                                <td><?= \yii\helpers\Html::encode($adaptador->idAdaptador) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->MARCA) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->MODELO) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->TIPO) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->VOLTAJE) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->AMPERAJE) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->POTENCIA_WATTS) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->NUMERO_SERIE) ?></td>
                                <td><?= \yii\helpers\Html::encode($adaptador->ubicacion_edificio) ?></td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <?= \yii\helpers\Html::encode($adaptador->ESTADO) ?>
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
                    No hay adaptadores en proceso de baja.
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
                alert('⚠️ Debes seleccionar al menos un adaptador.');
                return;
            }
            
            if (confirm(`¿Estás seguro de cambiar ${equiposSeleccionados.length} adaptador(es) al estado "BAJA"?`)) {
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
