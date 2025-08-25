<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $microfonos */
/** @var string|null $error */

$this->title = 'Gestión de Micrófonos';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Agregar estilos
$this->registerCss("
    .equipment-header {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
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
                        <i class="fas fa-microphone me-2"></i>Gestión de Micrófonos
                    </h3>
                    <p class="mb-0 mt-2">Equipos de Audio y Sonido</p>
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
                            <?php elseif (empty($microfonos)): ?>
                                <div class="alert alert-warning">
                                    <strong>📭 SIN EQUIPOS:</strong> No hay micrófonos registrados.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    <strong>✅ DATOS CARGADOS:</strong> <?= count($microfonos) ?> equipos encontrados
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= \yii\helpers\Url::to(['site/microfono-agregar']) ?>" class="btn btn-secondary btn-equipment me-2">
                                <i class="fas fa-plus me-2"></i>Agregar Micrófono
                            </a>
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
                                <input type="text" class="form-control" id="buscar_microfono" placeholder="Buscar por marca, modelo, tipo...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Micrófonos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Tipo</th>
                                    <th>N° Serie</th>
                                    <th>N° Inventario</th>
                                    <th>Estado</th>
                                    <th>Ubicación</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_microfonos">
                                <?php if (empty($microfonos) && !$error): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> No hay micrófonos registrados. Por favor, agregue algunos equipos para comenzar.
                                        </td>
                                    </tr>
                                <?php elseif ($error): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos: <?= Html::encode($error) ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($microfonos as $microfono): ?>
                                        <tr>
                                            <td><strong><?= Html::encode($microfono->idMicrofono) ?></strong></td>
                                            <td><?= Html::encode($microfono->MARCA ?? '-') ?></td>
                                            <td><?= Html::encode($microfono->MODELO ?? '-') ?></td>
                                            <td><?= Html::encode($microfono->TIPO ?? '-') ?></td>
                                            <td><?= Html::encode($microfono->NUMERO_SERIE ?? '-') ?></td>
                                            <td><?= Html::encode($microfono->NUMERO_INVENTARIO ?? '-') ?></td>
                                            <td>
                                                <?php
                                                $estado = strtolower($microfono->ESTADO ?? '');
                                                $badgeClass = match($estado) {
                                                    'activo' => 'bg-success',
                                                    'reparación', 'reparacion' => 'bg-warning',
                                                    'inactivo', 'dañado', 'danado' => 'bg-secondary',
                                                    'baja' => 'bg-danger',
                                                    default => 'bg-dark'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= Html::encode($microfono->ESTADO ?? '-') ?></span>
                                            </td>
                                            <td><?= Html::encode($microfono->ubicacion_edificio ?? '-') ?></td>
                                            <td><?= $microfono->FECHA ? date('d/m/Y', strtotime($microfono->FECHA)) : '-' ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-info" onclick="verDetalles(<?= $microfono->idMicrofono ?>)" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?= Html::a('<i class="fas fa-edit"></i>', 
                                                        ['site/microfono-editar', 'id' => $microfono->idMicrofono], 
                                                        ['class' => 'btn btn-sm btn-secondary', 'title' => 'Editar']) ?>
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
$this->registerJs("
// Datos de Micrófonos
let microfonosData = " . json_encode($microfonos, JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE) . ";

// Función de búsqueda
document.getElementById('buscar_microfono').addEventListener('input', function() {
    const filtro = this.value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tbody_microfonos tr');
    
    filas.forEach(fila => {
        if (fila.cells && fila.cells.length >= 10) {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = filtro === '' || texto.includes(filtro) ? '' : 'none';
        }
    });
});

// Función para ver detalles
function verDetalles(id) {
    const microfono = microfonosData.find(m => m.idMicrofono == id);
    if (microfono) {
        alert('📋 Detalles del Micrófono\\n\\n' +
              '🆔 ID: ' + (microfono.idMicrofono || 'N/A') + '\\n' +
              '🏷️ Marca: ' + (microfono.MARCA || 'N/A') + '\\n' +
              '📱 Modelo: ' + (microfono.MODELO || 'N/A') + '\\n' +
              '🎤 Tipo: ' + (microfono.TIPO || 'N/A') + '\\n' +
              '🔢 Serie: ' + (microfono.NUMERO_SERIE || 'N/A') + '\\n' +
              '📦 Inventario: ' + (microfono.NUMERO_INVENTARIO || 'N/A') + '\\n' +
              '🔄 Estado: ' + (microfono.Estado || 'N/A') + '\\n' +
              '🏢 Ubicación: ' + (microfono.ubicacion_edificio || 'N/A') + '\\n' +
              '📝 Descripción: ' + (microfono.DESCRIPCION || 'N/A'));
    }
}

console.log('✅ Sistema de Micrófonos cargado con', microfonosData.length, 'equipos');
");
?>
