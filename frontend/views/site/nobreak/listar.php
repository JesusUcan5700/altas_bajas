<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $nobreaks */
/** @var string|null $error */

$this->title = 'Gestión de No Break / UPS';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Agregar estilos
$this->registerCss("
    .equipment-header {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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
                        <i class="fas fa-battery-half me-2"></i>Gestión de No Break / UPS
                    </h3>
                    <p class="mb-0 mt-2">Sistemas de Alimentación Ininterrumpida</p>
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
                            <?php elseif (empty($nobreaks)): ?>
                                <div class="alert alert-warning">
                                    <strong>📭 SIN EQUIPOS:</strong> No hay No Break registrados.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    <strong>✅ DATOS CARGADOS:</strong> <?= count($nobreaks) ?> equipos encontrados
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
                    $equiposDanados = \frontend\models\Nobreak::getEquiposDanados();
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
                                <input type="text" class="form-control" id="buscar_nobreak" placeholder="Buscar por marca, modelo, serie...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de No Break -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Capacidad</th>
                                    <th>N° Serie</th>
                                    <th>N° Inventario</th>
                                    <th>Estado</th>
                                    <th>Ubicación</th>
                                    <th>Emisión</th>
                                    <th>Tiempo Activo</th>
                                    <th>Último Editor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_nobreak">
                                <?php if (empty($nobreaks) && !$error): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> No hay No Break registrados en el sistema. Por favor, agregue algunos equipos para comenzar.
                                        </td>
                                    </tr>
                                <?php elseif ($error): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos: <?= Html::encode($error) ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($nobreaks as $nobreak): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($nobreak['idNOBREAK']) ?></strong></td>
                                            <td><?= htmlspecialchars($nobreak['MARCA'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($nobreak['MODELO'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($nobreak['CAPACIDAD'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($nobreak['NUMERO_SERIE'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($nobreak['NUMERO_INVENTARIO'] ?? '-') ?></td>
                                            <td>
                                                <?php
                                                $estado = strtolower($nobreak['Estado'] ?? '');
                                                $badgeClass = match($estado) {
                                                    'activo' => 'bg-success',
                                                    'reparación', 'reparacion' => 'bg-warning',
                                                    'inactivo', 'dañado', 'danado' => 'bg-secondary',
                                                    'baja' => 'bg-danger',
                                                    default => 'bg-dark'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($nobreak['Estado'] ?? '-') ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($nobreak['ubicacion_edificio'] ?? '-') ?></td>
                                            <td><?= !empty($nobreak['EMISION_INVENTARIO']) ? date('d/m/Y', strtotime($nobreak['EMISION_INVENTARIO'])) : '-' ?></td>
                                            <td>
                                                <?php
                                                if (!empty($nobreak['EMISION_INVENTARIO'])) {
                                                    try {
                                                        $fechaEmision = new DateTime($nobreak['EMISION_INVENTARIO']);
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
                                                $editor = $nobreak['ultimo_editor'] ?? 'No especificado';
                                                if ($editor === 'Sistema') {
                                                    echo '<span class="badge bg-secondary">Sistema</span>';
                                                } else {
                                                    echo '<span class="badge bg-info">' . htmlspecialchars($editor) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-info" onclick="verDetalles(<?= $nobreak['idNOBREAK'] ?>)" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="<?= \yii\helpers\Url::to(['site/nobreak-editar', 'id' => $nobreak['idNOBREAK']]) ?>" class="btn btn-sm btn-warning" title="Editar">
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
                    <input type="hidden" name="modelo" value="Nobreak">
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
                                    <th>N° Serie</th>
                                    <th>N° Inventario</th>
                                    <th>Ubicación</th>
                                    <th>Emisión</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($equiposDanados as $equipo): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="equipos[]" value="<?= $equipo->idNOBREAK ?>" class="check-equipo">
                                    </td>
                                    <td><?= htmlspecialchars($equipo->idNOBREAK) ?></td>
                                    <td><?= htmlspecialchars($equipo->MARCA) ?></td>
                                    <td><?= htmlspecialchars($equipo->MODELO) ?></td>
                                    <td><?= htmlspecialchars($equipo->NUMERO_SERIE) ?></td>
                                    <td><?= htmlspecialchars($equipo->NUMERO_INVENTARIO) ?></td>
                                    <td>
                                        <?= htmlspecialchars($equipo->ubicacion_edificio) ?>
                                        <?php if ($equipo->ubicacion_detalle): ?>
                                            - <?= htmlspecialchars($equipo->ubicacion_detalle) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $equipo->EMISION_INVENTARIO ? date('d/m/Y', strtotime($equipo->EMISION_INVENTARIO)) : 'N/A' ?></td>
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

<?php
$this->registerJs("
// Datos de No Break
let nobreakData = " . json_encode($nobreaks, JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) . ";

// Función de búsqueda
document.getElementById('buscar_nobreak').addEventListener('input', function() {
    const filtro = this.value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbody_nobreak tr');
    
    filas.forEach(fila => {
        if (fila.cells && fila.cells.length >= 10) {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = filtro === '' || texto.includes(filtro) ? '' : 'none';
        }
    });
});

// Función para ver detalles
function verDetalles(id) {
    const nobreak = nobreakData.find(n => n.idNOBREAK == id);
    if (nobreak) {
        alert('📋 Detalles del No Break\\n\\n' +
              '🆔 ID: ' + (nobreak.idNOBREAK || 'N/A') + '\\n' +
              '🏷️ Marca: ' + (nobreak.MARCA || 'N/A') + '\\n' +
              '📱 Modelo: ' + (nobreak.MODELO || 'N/A') + '\\n' +
              '⚡ Capacidad: ' + (nobreak.CAPACIDAD || 'N/A') + '\\n' +
              '🔢 Serie: ' + (nobreak.NUMERO_SERIE || 'N/A') + '\\n' +
              '📦 Inventario: ' + (nobreak.NUMERO_INVENTARIO || 'N/A') + '\\n' +
              '🔄 Estado: ' + (nobreak.Estado || 'N/A') + '\\n' +
              '🏢 Ubicación: ' + (nobreak.ubicacion_edificio || 'N/A') + '\\n' +
              '� Detalle: ' + (nobreak.ubicacion_detalle || 'N/A') + '\\n' +
              '📅 Emisión: ' + (nobreak.EMISION_INVENTARIO || 'N/A') + '\\n' +
              '�📝 Descripción: ' + (nobreak.DESCRIPCION || 'N/A'));
    }
}

console.log('✅ Sistema de No Break cargado con', nobreakData.length, 'equipos');

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
    
    return confirm(`¿Está seguro que desea cambiar el estado de \${checks.length} equipo(s) a 'BAJA'?\\n\\nEsta acción no se puede deshacer.`);
}
");
?>
