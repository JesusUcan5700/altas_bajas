<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/* @var $equipos array */
/* @var $error string|null */

$this->title = 'Gestión de Equipos de Cómputo';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Función para calcular días activos directamente
function calcularDiasActivo($fechaEmision) {
    if (empty($fechaEmision)) {
        return 0;
    }
    
    try {
        $fechaEmisionObj = new \DateTime($fechaEmision);
        $fechaActual = new \DateTime();
        $diferencia = $fechaActual->getTimestamp() - $fechaEmisionObj->getTimestamp();
        $dias = floor($diferencia / (60 * 60 * 24));
        return max(0, $dias);
    } catch (Exception $e) {
        return 0;
    }
}

function calcularAnosActivo($dias) {
    if ($dias == 0) return 0;
    return round($dias / 365.25, 2);
}

function formatearAnosTexto($dias) {
    if ($dias == 0) return 'Sin fecha';
    $anos = calcularAnosActivo($dias);
    if ($anos < 1) return 'Menos de 1 año';
    if ($anos == 1) return '1 año';
    return sprintf('%.1f años', $anos);
}

// Agregar estilos
$this->registerCss("
    .equipment-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
                        <i class="fas fa-desktop me-2"></i>Gestión de Equipos de Cómputo
                    </h3>
                    <p class="mb-0 mt-2">Computadoras, Laptops y Servidores</p>
                </div>
                <div class="card-body">
                    <!-- Recuadro de Equipos Dañados -->
                    <?php 
                    $equiposDanados = \frontend\models\Equipo::getEquiposDanados();
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

                    <!-- Panel de información de última actividad -->
                    <?php if ($ultimaModificacion): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-primary d-flex align-items-center" role="alert">
                                    <i class="fas fa-edit me-3 fs-4"></i>
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-1">
                                                    <strong>Último equipo editado:</strong> 
                                                    <span class="badge bg-success me-2">ID: <?= $ultimaModificacion['id'] ?></span>
                                                    <?= htmlspecialchars($ultimaModificacion['equipo']) ?>
                                                </div>
                                                <small class="text-dark">
                                                    <i class="fas fa-user me-1"></i>
                                                    Editado por: <strong><?= htmlspecialchars($ultimaModificacion['usuario_display']) ?></strong>
                                                    <?php if (!empty($ultimaModificacion['usuario_email'])): ?>
                                                        <span class="text-muted">(<?= htmlspecialchars($ultimaModificacion['usuario_email']) ?>)</span>
                                                    <?php endif; ?>
                                                    <br>
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?= $ultimaModificacion['fecha_formateada'] ?> - <?= $ultimaModificacion['tiempo_transcurrido'] ?>
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <div class="d-flex justify-content-md-end align-items-center gap-3">
                                                    <div class="text-center">
                                                        <div class="fw-bold fs-5 text-success"><?= $ultimaModificacion['total_equipos'] ?></div>
                                                        <small class="text-dark">Total Equipos</small>
                                                    </div>
                                                    <div class="text-center">
                                                        <div class="fw-bold fs-5 text-primary"><?= $ultimaModificacion['equipos_activos'] ?></div>
                                                        <small class="text-dark">Activos</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

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
                            <?php elseif (empty($equipos)): ?>
                                <div class="alert alert-warning">
                                    <strong>📭 SIN EQUIPOS:</strong> No hay equipos de cómputo registrados.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    <strong>✅ DATOS CARGADOS:</strong> <?= count($equipos) ?> equipos encontrados
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="btn btn-secondary btn-equipment">
                                <i class="fas fa-arrow-left me-2"></i>Volver al Menú
                            </a>
                        </div>
                    </div>

                    <!-- Buscador -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="buscar_equipo" placeholder="Buscar por marca, modelo, CPU, RAM, discos duros...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Equipos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>CPU</th>
                                    <th>Memoria RAM</th>
                                    <th>Almacenamiento</th>
                                    <th>N° Serie</th>
                                    <th>N° Inventario</th>
                                    <th>Emisión</th>
                                    <th>Tiempo Activo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_equipos">
                                <?php if (empty($equipos) && !$error): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> No hay equipos de cómputo registrados en el sistema. Por favor, agregue algunos equipos para comenzar.
                                        </td>
                                    </tr>
                                <?php elseif ($error): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos: <?= Html::encode($error) ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($equipos as $equipo): ?>
                                        <?php 
                                        $diasActivo = calcularDiasActivo($equipo['EMISION_INVENTARIO']);
                                        ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($equipo['idEQUIPO']) ?></strong></td>
                                            <td><?= htmlspecialchars($equipo['MARCA'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($equipo['MODELO'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($equipo['CPU'] ?? '-') ?></td>
                                            
                                            <!-- Columna Memoria RAM -->
                                            <td>
                                                <?php
                                                $rams = [];
                                                if (!empty($equipo['RAM'])) $rams[] = $equipo['RAM'];
                                                if (!empty($equipo['RAM2']) && $equipo['RAM2'] !== 'NO') $rams[] = $equipo['RAM2'];
                                                if (!empty($equipo['RAM3']) && $equipo['RAM3'] !== 'NO') $rams[] = $equipo['RAM3'];
                                                if (!empty($equipo['RAM4']) && $equipo['RAM4'] !== 'NO') $rams[] = $equipo['RAM4'];
                                                ?>
                                                <?php if (!empty($rams)): ?>
                                                    <?php foreach ($rams as $index => $ram): ?>
                                                        <div class="mb-1">
                                                            <span class="badge bg-<?= $index === 0 ? 'primary' : 'secondary' ?> text-white">
                                                                <i class="fas fa-memory me-1"></i>
                                                                RAM<?= $index > 0 ? ($index + 1) : '' ?>: <?= htmlspecialchars($ram) ?>
                                                            </span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <!-- Columna Almacenamiento -->
                                            <td>
                                                <?php
                                                $discos = [];
                                                if (!empty($equipo['DD'])) $discos[] = $equipo['DD'];
                                                if (!empty($equipo['DD2']) && $equipo['DD2'] !== 'NO') $discos[] = $equipo['DD2'];
                                                if (!empty($equipo['DD3']) && $equipo['DD3'] !== 'NO') $discos[] = $equipo['DD3'];
                                                if (!empty($equipo['DD4']) && $equipo['DD4'] !== 'NO') $discos[] = $equipo['DD4'];
                                                ?>
                                                <?php if (!empty($discos)): ?>
                                                    <?php foreach ($discos as $index => $disco): ?>
                                                        <div class="mb-1">
                                                            <span class="badge bg-<?= $index === 0 ? 'success' : 'info' ?> text-white">
                                                                <i class="fas fa-hdd me-1"></i>
                                                                DD<?= $index > 0 ? ($index + 1) : '' ?>: <?= htmlspecialchars($disco) ?>
                                                            </span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($equipo['NUM_SERIE'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($equipo['NUM_INVENTARIO'] ?? '-') ?></td>
                                            <td>
                                                <?php if (!empty($equipo['EMISION_INVENTARIO'])): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?= date('d/m/Y', strtotime($equipo['EMISION_INVENTARIO'])) ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($equipo['EMISION_INVENTARIO'])): ?>
                                                    <div class="text-center">
                                                        <div class="fw-bold text-primary"><?= $diasActivo ?> días</div>
                                                        <small class="text-muted"><?= formatearAnosTexto($diasActivo) ?></small>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $estado = strtolower($equipo['Estado'] ?? '');
                                                $badgeClass = match($estado) {
                                                    'activo' => 'bg-success',
                                                    'reparación', 'reparacion' => 'bg-warning',
                                                    'inactivo', 'dañado', 'danado' => 'bg-secondary',
                                                    'baja' => 'bg-danger',
                                                    default => 'bg-dark'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($equipo['Estado'] ?? '-') ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= \yii\helpers\Url::to(['site/equipo-editar', 'id' => $equipo['idEQUIPO']]) ?>" class="btn btn-sm btn-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
// Datos de Equipos
let equiposData = " . json_encode($equipos, JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) . ";

// Función de búsqueda
document.getElementById('buscar_equipo').addEventListener('input', function() {
    const filtro = this.value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbody_equipos tr');
    
    filas.forEach(fila => {
        if (fila.cells && fila.cells.length >= 13) {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = filtro === '' || texto.includes(filtro) ? '' : 'none';
        }
    });
});

console.log('✅ Sistema de Equipos de Cómputo cargado con', equiposData.length, 'equipos');
");
?>
