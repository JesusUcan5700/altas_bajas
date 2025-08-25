<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Stock Disponible por Categoría';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js');

// Cargar datos de stock por categoría
$categorias = [
    'nobreak' => [
        'nombre' => 'No Break / UPS',
        'icono' => 'fas fa-battery-half',
        'color' => 'warning',
        'modelo' => 'frontend\models\Nobreak',
        'tabla' => 'nobreak',
        'id_field' => 'idNOBREAK'
    ],
    'equipo' => [
        'nombre' => 'Equipos de Cómputo',
        'icono' => 'fas fa-desktop',
        'color' => 'primary',
        'modelo' => 'frontend\models\Equipo',
        'tabla' => 'equipo',
        'id_field' => 'idEQUIPO'
    ],
    'impresora' => [
        'nombre' => 'Impresoras',
        'icono' => 'fas fa-print',
        'color' => 'info',
        'modelo' => 'frontend\models\Impresora',
        'tabla' => 'impresora',
        'id_field' => 'idIMPRESORA'
    ],
    'monitor' => [
        'nombre' => 'Monitores',
        'icono' => 'fas fa-tv',
        'color' => 'success',
        'modelo' => 'frontend\\models\\Monitor',
        'tabla' => 'monitor',
        'id_field' => 'idMonitor'
    ],
    'adaptadores' => [
        'nombre' => 'Adaptadores',
        'icono' => 'fas fa-plug',
        'color' => 'dark',
        'modelo' => 'frontend\models\Adaptador',
        'tabla' => 'adaptadores',
        'id_field' => 'id'
    ]
];

// Función para obtener estadísticas de stock por categoría
function obtenerStockCategoria($categoria) {
    try {
        $connection = Yii::$app->db;
        $tabla = $categoria['tabla'];
        
        // Mapeo específico de campos de estado por tabla
        $camposEstado = [
            'nobreak' => 'Estado',
            'equipo' => 'Estado', 
            'impresora' => 'Estado',
            'monitor' => 'ESTADO',
            'baterias' => 'estado',
            'almacenamiento' => 'ESTADO',
            'memoria_ram' => 'estado',
            'equipo_sonido' => 'estado',
            'procesadores' => 'estado',
            'conectividad' => 'Estado',
            'telefonia' => 'ESTADO',
            'video_vigilancia' => 'estado',
            'adaptadores' => 'estado'
        ];
        
        $campoEstado = isset($camposEstado[$tabla]) ? $camposEstado[$tabla] : 'Estado';
        
        // Verificar si la tabla existe
        $tablaExiste = $connection->createCommand("SHOW TABLES LIKE '$tabla'")->queryOne();
        if (!$tablaExiste) {
            return [
                'total' => 0,
                'activos' => 0,
                'disponibles' => 0,
                'danados' => 0,
                'mantenimiento' => 0,
                'error' => "Tabla '$tabla' no existe en la base de datos"
            ];
        }
        
        // Consulta para obtener estadísticas
        $sql = "
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN $campoEstado = 'Activo' THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN $campoEstado IN ('Inactivo', 'Inactivo(Sin Asignar)', 'Disponible') THEN 1 ELSE 0 END) as disponibles,
                SUM(CASE WHEN $campoEstado LIKE '%dañado%' OR $campoEstado LIKE '%baja%' OR $campoEstado LIKE '%Dañado%' THEN 1 ELSE 0 END) as danados,
                SUM(CASE WHEN $campoEstado LIKE '%mantenimiento%' OR $campoEstado = 'Reparación' OR $campoEstado LIKE '%Mantenimiento%' THEN 1 ELSE 0 END) as mantenimiento
            FROM $tabla
        ";
        
        $resultado = $connection->createCommand($sql)->queryOne();
        
        return [
            'total' => (int)$resultado['total'],
            'activos' => (int)$resultado['activos'],
            'disponibles' => (int)$resultado['disponibles'],
            'danados' => (int)$resultado['danados'],
            'mantenimiento' => (int)$resultado['mantenimiento'],
            'error' => null
        ];
        
    } catch (Exception $e) {
        return [
            'total' => 0,
            'activos' => 0,
            'disponibles' => 0,
            'danados' => 0,
            'mantenimiento' => 0,
            'error' => $e->getMessage()
        ];
    }
}

// Obtener estadísticas de todas las categorías
$stockData = [];
$totalGeneral = 0;
$activosGeneral = 0;
$disponiblesGeneral = 0;
$danadosGeneral = 0;
$mantenimientoGeneral = 0;

foreach ($categorias as $key => $categoria) {
    $stock = obtenerStockCategoria($categoria);
    $stockData[$key] = array_merge($categoria, $stock);
    
    $totalGeneral += $stock['total'];
    $activosGeneral += $stock['activos'];
    $disponiblesGeneral += $stock['disponibles'];
    $danadosGeneral += $stock['danados'];
    $mantenimientoGeneral += $stock['mantenimiento'];
}

// Registrar estilos (mejorados)
$this->registerCss("\
    /* Header */\n
    .stock-header {\n
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);\n
        color: white;\n
        border-radius: 12px 12px 0 0;\n
        padding: 1rem 1.25rem;\n
    }\n
    /* Main card */\n
    .stock-card { border: none; border-radius: 12px; box-shadow: 0 6px 18px rgba(16,24,40,0.06); transition: transform 0.18s ease-in-out, box-shadow 0.18s ease-in-out; }\n
    .stock-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(16,24,40,0.12); }\n
    /* Category cards */\n
    .category-card { border: none; border-radius: 10px; overflow: visible; position: relative; }\n
    .category-card .card-header { position: relative; padding: 0.9rem 1rem; }\n
    .category-card .badge-count { position: absolute; right: 12px; top: 10px; background: rgba(255,255,255,0.95); color: #111; font-weight: 600; padding: 0.35rem 0.6rem; border-radius: 999px; font-size: 0.78rem; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }\n
    .category-card .card-body { padding: 1rem; }\n
    .category-card .card-footer { background: transparent; border-top: none; padding: 0.8rem 1rem 1rem; }\n
    .stats-icon { font-size: 1.9rem; opacity: 0.95; }\n+    .category-icon { font-size: 2.2rem; margin-bottom: 0.6rem; }\n
    .progress-custom { height: 8px; border-radius: 6px; background: #f1f5f9; }\n
    .btn-detail { border-radius: 999px; padding: 8px 18px; font-weight: 600; }\n
    .summary-stats { background: #fbfbfd; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.25rem; }\n
    .filter-section { background: #fff; border-radius: 10px; padding: 1rem; margin-bottom: 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);}\n
    @media (max-width: 576px) { .category-card .badge-count { right: 8px; top: 8px; font-size: 0.72rem; } .category-card .card-body { padding: 0.75rem; } }\n");
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card stock-card">
                <div class="card-header stock-header text-center">
                    <h2 class="mb-0">
                        <i class="fas fa-boxes me-3"></i>Stock Disponible por Categoría
                    </h2>
                    <p class="mb-0 mt-2">Control de inventario y disponibilidad de equipos</p>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Resumen General -->
                    <div class="summary-stats">
                        <h4 class="text-center mb-4">
                            <i class="fas fa-chart-pie me-2"></i>Resumen General del Inventario
                        </h4>
                        <div class="row text-center">
                            <div class="col-md-2 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-boxes stats-icon text-primary"></i>
                                    <h3 class="mt-2 mb-1 text-primary"><?= $totalGeneral ?></h3>
                                    <small class="text-muted">Total Equipos</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-check-circle stats-icon text-success"></i>
                                    <h3 class="mt-2 mb-1 text-success"><?= $activosGeneral ?></h3>
                                    <small class="text-muted">En Uso</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-warehouse stats-icon text-info"></i>
                                    <h3 class="mt-2 mb-1 text-info"><?= $disponiblesGeneral ?></h3>
                                    <small class="text-muted">Disponibles</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-tools stats-icon text-warning"></i>
                                    <h3 class="mt-2 mb-1 text-warning"><?= $mantenimientoGeneral ?></h3>
                                    <small class="text-muted">Mantenimiento</small>
                                </div>
                            </div>
                           <div class="col-md-2 mb-3">
                               <div class="p-3 bg-light rounded">
                                   <h3 class="mt-2 mb-1 text-primary"><?= $totalGeneral ?></h3>
                            <div class="col-md-2 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-exclamation-triangle stats-icon text-danger"></i>
                                    <h3 class="mt-2 mb-1 text-danger"><?= $danadosGeneral ?></h3>
                                    <small class="text-muted">Dañados/Baja</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-percentage stats-icon text-secondary"></i>
                                    <h3 class="mt-2 mb-1 text-secondary">
                                        <?= $totalGeneral > 0 ? round(($disponiblesGeneral / $totalGeneral) * 100, 1) : 0 ?>%
                                    </h3>
                                    <small class="text-muted">Disponibilidad</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtros y Búsqueda -->
                    <div class="filter-section">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="buscarCategoria" 
                                           placeholder="Buscar categoría...">
                                           placeholder="Buscar categoría...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filtroEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="disponible">Solo disponibles</option>
                                    <option value="critico">Stock crítico (&lt;5)</option>
                                    <option value="vacio">Sin equipos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="selectorCategoria">
                                    <option value="">-- Seleccionar categoría --</option>
                                    <?php foreach ($stockData as $k => $d): ?>
                                        <option value="<?= htmlspecialchars($k) ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="soloInactivos">
                                    <label class="form-check-label small" for="soloInactivos">Solo inactivos</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="ordenar">
                                    <option value="nombre">Ordenar por nombre</option>
                                    <option value="total_desc">Total (mayor a menor)</option>
                                    <option value="total_asc">Total (menor a mayor)</option>
                                    <option value="disponibles_desc">Disponibles (mayor a menor)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle w-100" type="button" id="btnExportar" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-file-excel me-2"></i>Exportar
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="abrirModalExportar('csv')">
                                            <i class="fas fa-file-excel me-2 text-success"></i>Excel (CSV)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="abrirModalExportar('json')">
                                            <i class="fas fa-file-code me-2 text-info"></i>JSON
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="imprimirReporte()">
                                            <i class="fas fa-print me-2 text-secondary"></i>Imprimir
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grid de Categorías -->
                    <div class="row" id="categoriasGrid">
                        <?php foreach ($stockData as $key => $data): ?>
                            <div class="col-lg-4 col-md-6 mb-4 categoria-item" 
                                 data-categoria="<?= htmlspecialchars($data['nombre']) ?>"
                                 data-total="<?= $data['total'] ?>"
                                 data-disponibles="<?= $data['disponibles'] ?>">
                                <div class="card category-card border-<?= $data['color'] ?>">
                                    <div class="card-header bg-<?= $data['color'] ?> text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <i class="<?= $data['icono'] ?> me-2"></i><?= $data['nombre'] ?>
                                            </h6>
                                            <?php if ($data['error']): ?>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-exclamation-triangle"></i> Error
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">
                                                    <?= $data['total'] ?> equipos
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($data['error']): ?>
                                            <div class="text-center text-muted">
                                                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                                <p class="small">Error al cargar datos:<br><?= htmlspecialchars($data['error']) ?></p>
                                            </div>
                                        <?php else: ?>
                                            <!-- Estadísticas -->
                                            <div class="row text-center mb-3">
                                                <div class="col-6">
                                                    <h4 class="mb-1 text-success"><?= $data['activos'] ?></h4>
                                                    <small class="text-muted">En Uso</small>
                                                </div>
                                                <div class="col-6">
                                                    <h4 class="mb-1 text-info"><?= $data['disponibles'] ?></h4>
                                                    <small class="text-muted">Disponibles</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Barra de progreso -->
                                            <?php 
                                            $porcentajeUso = $data['total'] > 0 ? ($data['activos'] / $data['total']) * 100 : 0;
                                            $porcentajeDisponible = $data['total'] > 0 ? ($data['disponibles'] / $data['total']) * 100 : 0;
                                            ?>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between small text-muted mb-1">
                                                    <span>Utilización</span>
                                                    <span><?= round($porcentajeUso, 1) ?>%</span>
                                                </div>
                                                <div class="progress progress-custom">
                                                    <div class="progress-bar bg-success" style="width: <?= $porcentajeUso ?>%"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Detalles adicionales -->
                                            <?php if ($data['mantenimiento'] > 0 || $data['danados'] > 0): ?>
                                                <div class="row text-center small">
                                                    <?php if ($data['mantenimiento'] > 0): ?>
                                                        <div class="col-6">
                                                            <span class="badge bg-warning"><?= $data['mantenimiento'] ?> Mantenimiento</span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($data['danados'] > 0): ?>
                                                        <div class="col-6">
                                                            <span class="badge bg-danger"><?= $data['danados'] ?> Dañados</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <div class="d-grid gap-2 d-md-flex">
                                            <?php 
                                            $urlMap = [
                                                'nobreak' => 'nobreak-listar',
                                                'equipo' => 'equipo-listar',
                                                'impresora' => 'impresora-listar',
                                                'monitor' => 'monitor-listar',
                                                'baterias' => 'baterias-listar',
                                                'almacenamiento' => 'almacenamiento-listar',
                                                'memoria_ram' => 'ram-listar',
                                                'equipo_sonido' => 'sonido-listar',
                                                'procesadores' => 'procesador-listar',
                                                'conectividad' => 'conectividad-listar',
                                                'telefonia' => 'telefonia-listar',
                                                'video_vigilancia' => 'videovigilancia-listar',
                                                'adaptadores' => 'adaptadores-listar'
                                            ];
                                            $listUrl = isset($urlMap[$key]) ? $urlMap[$key] : 'gestion-categorias';
                                            ?>
                                            <a href="<?= Url::to(['site/' . $listUrl]) ?>" 
                                               class="btn btn-outline-<?= $data['color'] ?> btn-detail me-2 flex-fill">
                                                <i class="fas fa-list me-1"></i>Ver Detalle
                                            </a>
                                            <button class="btn btn-<?= $data['color'] ?> btn-detail flex-fill"
                                                    onclick="verEstadisticas('<?= $key ?>', '<?= addslashes($data['nombre']) ?>')">
                                                <i class="fas fa-chart-bar me-1"></i>Stats
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Botones de navegación -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <div class="btn-group" role="group">
                                <a href="<?= Url::to(['site/index']) ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al Menú
                                </a>
                                <a href="<?= Url::to(['site/gestion-categorias']) ?>" class="btn btn-primary">
                                    <i class="fas fa-cogs me-2"></i>Gestión por Categorías
                                </a>
                                <button class="btn btn-info" onclick="actualizarDatos()">
                                    <i class="fas fa-sync-alt me-2"></i>Actualizar Datos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para estadísticas detalladas -->
<div class="modal fade" id="modalEstadisticas" tabindex="-1" aria-labelledby="modalEstadisticasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEstadisticasLabel">
                    <i class="fas fa-chart-bar me-2"></i>Estadísticas Detalladas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalEstadisticasContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                    <!-- Modal Exportar por Categoría -->
                    <div class="modal fade" id="modalExportarCategoria" tabindex="-1" aria-labelledby="modalExportarCategoriaLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalExportarCategoriaLabel">Exportar por Categoría</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Categoría</label>
                                        <select id="exportCategoriaSelect" class="form-select">
                                            <option value="">-- Seleccione --</option>
                                            <?php foreach ($stockData as $k => $d): ?>
                                                <option value="<?= htmlspecialchars($k) ?>"><?= htmlspecialchars($d['nombre']) ?> (<?= $d['total'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Formato</label>
                                        <div id="exportFormato" class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary" id="formatoCsvBtn">CSV</button>
                                            <button type="button" class="btn btn-outline-primary" id="formatoJsonBtn">JSON</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" id="btnExportarCategoria">Exportar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Inactivos por categoría -->
                    <div class="modal fade" id="modalInactivos" tabindex="-1" aria-labelledby="modalInactivosLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalInactivosLabel"><i class="fas fa-box-open me-2"></i>Dispositivos Inactivos</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" id="modalInactivosBody">
                                    <div class="text-center">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

<?php
$stockDataJson = json_encode($stockData, JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);
$script = <<<JS
// Datos de stock
const stockData = $stockDataJson;

// Funcionalidad de búsqueda
document.getElementById('buscarCategoria').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    filtrarCategorias();
});

// Filtro por estado
document.getElementById('filtroEstado').addEventListener('change', function() {
    filtrarCategorias();
});

// Ordenamiento
document.getElementById('ordenar').addEventListener('change', function() {
    ordenarCategorias();
});

function filtrarCategorias() {
    const busqueda = document.getElementById('buscarCategoria').value.toLowerCase();
    const filtroEstado = document.getElementById('filtroEstado').value;
    const items = document.querySelectorAll('.categoria-item');
    
    items.forEach(item => {
        const nombre = item.dataset.categoria.toLowerCase();
        const total = parseInt(item.dataset.total);
        const disponibles = parseInt(item.dataset.disponibles);
        
        let mostrar = true;
        
        // Filtro de búsqueda
        if (busqueda && !nombre.includes(busqueda)) {
            mostrar = false;
        }
        
        // Filtro por estado
        if (filtroEstado === 'disponible' && disponibles === 0) {
            mostrar = false;
        } else if (filtroEstado === 'critico' && total >= 5) {
            mostrar = false;
        } else if (filtroEstado === 'vacio' && total > 0) {
            mostrar = false;
        }
        
        item.style.display = mostrar ? 'block' : 'none';
    });
}

function ordenarCategorias() {
    const criterio = document.getElementById('ordenar').value;
    const container = document.getElementById('categoriasGrid');
    const items = Array.from(container.children);
    
    items.sort((a, b) => {
        const totalA = parseInt(a.dataset.total);
        const totalB = parseInt(b.dataset.total);
        const disponiblesA = parseInt(a.dataset.disponibles);
        const disponiblesB = parseInt(b.dataset.disponibles);
        const nombreA = a.dataset.categoria;
        const nombreB = b.dataset.categoria;
        
        switch(criterio) {
            case 'total_desc':
                return totalB - totalA;
            case 'total_asc':
                return totalA - totalB;
            case 'disponibles_desc':
                return disponiblesB - disponiblesA;
            case 'nombre':
            default:
                return nombreA.localeCompare(nombreB);
        }
    });
    
    items.forEach(item => container.appendChild(item));
}

function verEstadisticas(categoria, nombre) {
    const modal = new bootstrap.Modal(document.getElementById('modalEstadisticas'));
    const content = document.getElementById('modalEstadisticasContent');
    const title = document.getElementById('modalEstadisticasLabel');
    
    title.innerHTML = '<i class="fas fa-chart-bar me-2"></i>Estadísticas: ' + nombre;
    
    const data = stockData[categoria];
    
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <canvas id="chartEstados" width="200" height="200"></canvas>
            </div>
            <div class="col-md-6">
                <h6>Resumen Numérico</h6>
                <table class="table table-sm">
                    <tr><td>Total de Equipos:</td><td><strong>\${data.total}</strong></td></tr>
                    <tr><td>En Uso (Activos):</td><td><span class="text-success">\${data.activos}</span></td></tr>
                    <tr><td>Disponibles:</td><td><span class="text-info">\${data.disponibles}</span></td></tr>
                    <tr><td>En Mantenimiento:</td><td><span class="text-warning">\${data.mantenimiento}</span></td></tr>
                    <tr><td>Dañados/Baja:</td><td><span class="text-danger">\${data.danados}</span></td></tr>
                </table>
                
                <h6 class="mt-3">Indicadores</h6>
                <div class="mb-2">
                    <small>Tasa de Utilización</small>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: \${data.total > 0 ? (data.activos/data.total)*100 : 0}%"></div>
                    </div>
                    <small class="text-muted">\${data.total > 0 ? Math.round((data.activos/data.total)*100) : 0}%</small>
                </div>
                
                <div class="mb-2">
                    <small>Disponibilidad</small>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: \${data.total > 0 ? (data.disponibles/data.total)*100 : 0}%"></div>
                    </div>
                    <small class="text-muted">\${data.total > 0 ? Math.round((data.disponibles/data.total)*100) : 0}%</small>
                </div>
            </div>
        </div>
    `;
    
    modal.show();
}

// Mostrar inactivos por categoría
function mostrarInactivosPorCategoria() {
    const categoriaKey = document.getElementById('selectorCategoria').value;
    const soloInactivos = document.getElementById('soloInactivos').checked;

    if (!categoriaKey) {
        alert('Seleccione una categoría primero');
        return;
    }

    const data = stockData[categoriaKey];
    if (!data) {
        alert('No se encontraron datos para la categoría seleccionada');
        return;
    }

    // Si stockData incluye detalle de dispositivos (por ejemplo data.items), filtramos localmente
    if (data.items && Array.isArray(data.items)) {
        const items = soloInactivos ? data.items.filter(i => (i.Estado || '').toLowerCase() !== 'activo') : data.items;
        llenarModalInactivos(items, data.nombre);
        return;
    }
    // Si no hay detalles en stockData, pedirlos al servidor vía endpoint
    const modalBody = document.getElementById('modalInactivosBody');
    modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
    const modal = new bootstrap.Modal(document.getElementById('modalInactivos'));
    modal.show();

    // Construir URL del endpoint en el servidor
    const endpoint = '<?= Url::to(["site/stock-inactivos"]) ?>' + '?categoria=' + encodeURIComponent(categoriaKey);

    fetch(endpoint, { method: 'GET', credentials: 'same-origin' })
        .then(resp => resp.json())
        .then(result => {
            if (result && result.error === false && Array.isArray(result.items)) {
                const items = soloInactivos ? result.items.filter(i => (i.Estado || i.ESTADO || '').toString().toLowerCase() !== 'activo') : result.items;
                llenarModalInactivos(items, result.nombre || data.nombre);
            } else if (result && result.error === false && Array.isArray(result.items) === false) {
                modalBody.innerHTML = '<div class="alert alert-warning">No se encontraron elementos para la categoría seleccionada.</div>';
            } else {
                const msg = (result && result.message) ? result.message : 'Respuesta inválida del servidor.';
                modalBody.innerHTML = '<div class="alert alert-danger">Error: ' + msg + '</div>';
            }
        })
        .catch(err => {
            console.error('Error fetch stock-inactivos:', err);
            modalBody.innerHTML = '<div class="alert alert-danger">Ocurrió un error al solicitar los datos al servidor.</div>';
        });
}

function llenarModalInactivos(items, nombreCategoria) {
    const modalBody = document.getElementById('modalInactivosBody');
    if (!items || items.length === 0) {
        modalBody.innerHTML = '<div class="alert alert-warning">No se encontraron dispositivos inactivos en la categoría "'+nombreCategoria+'".</div>';
    } else {
        let html = '<div class="table-responsive"><table class="table table-sm table-striped"><thead><tr><th>ID</th><th>Nombre/Modelo</th><th>Estado</th><th>Ubicación</th></tr></thead><tbody>';
        items.forEach(it => {
            html += '<tr>'+
                    '<td>'+ (it.idEQUIPO || it.id || it.idAdaptador || '') +'</td>'+
                    '<td>'+ (it.MODELO || it.MODELO || it.nombre || it.NOMBRE || '-') +'</td>'+
                    '<td>'+ (it.Estado || it.ESTADO || it.estado || '-') +'</td>'+
                    '<td>'+ (it.ubicacion_edificio || it.ubicacion || '-') +'</td>'+
                    '</tr>';
        });
        html += '</tbody></table></div>';
        modalBody.innerHTML = html;
    }

    const modal = new bootstrap.Modal(document.getElementById('modalInactivos'));
    modal.show();
}

// Exportar por categoría
function abrirModalExportar(formato) {
    // preset formato seleccionado
    document.getElementById('formatoCsvBtn').classList.remove('active');
    document.getElementById('formatoJsonBtn').classList.remove('active');
    if (formato === 'csv') document.getElementById('formatoCsvBtn').classList.add('active');
    if (formato === 'json') document.getElementById('formatoJsonBtn').classList.add('active');
    const modal = new bootstrap.Modal(document.getElementById('modalExportarCategoria'));
    modal.show();
}

document.getElementById('formatoCsvBtn').addEventListener('click', function(){
    this.classList.add('active');
    document.getElementById('formatoJsonBtn').classList.remove('active');
});
document.getElementById('formatoJsonBtn').addEventListener('click', function(){
    this.classList.add('active');
    document.getElementById('formatoCsvBtn').classList.remove('active');
});

document.getElementById('btnExportarCategoria').addEventListener('click', function(){
    const key = document.getElementById('exportCategoriaSelect').value;
    if (!key) { alert('Seleccione una categoría'); return; }
    const formato = document.getElementById('formatoJsonBtn').classList.contains('active') ? 'json' : 'csv';
    const data = stockData[key];
    if (!data) { alert('Datos no disponibles para la categoría'); return; }

    if (formato === 'json') {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'stock_' + key + '.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    } else {
        // CSV: si data.items existe, exportar filas; si no, exportar resumen simple
        const rows = [];
        if (Array.isArray(data.items) && data.items.length > 0) {
            const headers = Object.keys(data.items[0]);
            rows.push(headers);
            data.items.forEach(it => {
                rows.push(headers.map(h => it[h] || ''));
            });
        } else {
            rows.push(['Categoría','Total','Activos','Disponibles','Mantenimiento','Dañados']);
            rows.push([data.nombre, data.total, data.activos, data.disponibles, data.mantenimiento, data.danados]);
        }

        const csv = rows.map(r => r.map(c => '"' + String(c).replace(/"/g,'""') + '"').join(',')).join('\n');
        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'stock_' + key + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Cerrar modal
    const modalEl = document.getElementById('modalExportarCategoria');
    const modalInst = bootstrap.Modal.getInstance(modalEl);
    if (modalInst) modalInst.hide();
});

function exportarExcel() {
    // Mostrar indicador de carga
    const btnExportar = document.getElementById('btnExportar');
    if (btnExportar) {
        const textoOriginal = btnExportar.innerHTML;
        btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generando...';
        btnExportar.disabled = true;
        
        // Restaurar botón después de un tiempo
        setTimeout(() => {
            btnExportar.innerHTML = textoOriginal;
            btnExportar.disabled = false;
        }, 2000);
    }
    
    try {
        // Crear datos para exportar
        const datos = [];
        const fecha = new Date().toLocaleDateString('es-ES');
        const hora = new Date().toLocaleTimeString('es-ES');
        
        // Agregar encabezado con información general
        datos.push(['REPORTE DE STOCK POR CATEGORÍA']);
        datos.push(['Fecha de generación:', fecha + ' ' + hora]);
        datos.push(['Sistema de Inventario - Control de Equipos']);
        datos.push([]);
        
        // Agregar resumen general
        let totalGeneral = 0;
        let activosGeneral = 0;
        let disponiblesGeneral = 0;
        let mantenimientoGeneral = 0;
        let danadosGeneral = 0;
        
        Object.keys(stockData).forEach(key => {
            const data = stockData[key];
            totalGeneral += data.total;
            activosGeneral += data.activos;
            disponiblesGeneral += data.disponibles;
            mantenimientoGeneral += data.mantenimiento;
            danadosGeneral += data.danados;
        });
        
        datos.push(['=== RESUMEN GENERAL ===']);
        datos.push(['Total de Equipos:', totalGeneral]);
        datos.push(['En Uso (Activos):', activosGeneral]);
        datos.push(['Disponibles:', disponiblesGeneral]);
        datos.push(['En Mantenimiento:', mantenimientoGeneral]);
        datos.push(['Dañados/Baja:', danadosGeneral]);
        datos.push(['Porcentaje de Disponibilidad:', totalGeneral > 0 ? Math.round((disponiblesGeneral/totalGeneral)*100) + '%' : '0%']);
        datos.push(['Porcentaje de Utilización:', totalGeneral > 0 ? Math.round((activosGeneral/totalGeneral)*100) + '%' : '0%']);
        datos.push([]);
        
        // Agregar encabezados de la tabla de categorías
        datos.push(['=== DETALLE POR CATEGORÍA ===']);
        datos.push(['Categoría', 'Total', 'En Uso', 'Disponibles', 'Mantenimiento', 'Dañados/Baja', 'Utilización %', 'Disponibilidad %', 'Estado']);
        
        // Agregar datos de cada categoría
        Object.keys(stockData).forEach(key => {
            const data = stockData[key];
            let estado = 'Normal';
            if (data.total === 0) estado = 'Sin Equipos';
            else if (data.disponibles === 0) estado = 'Sin Disponibles';
            else if (data.total < 5) estado = 'Stock Bajo';
            
            datos.push([
                data.nombre,
                data.total,
                data.activos,
                data.disponibles,
                data.mantenimiento,
                data.danados,
                data.total > 0 ? Math.round((data.activos/data.total)*100) + '%' : '0%',
                data.total > 0 ? Math.round((data.disponibles/data.total)*100) + '%' : '0%',
                estado
            ]);
        });
        
        // Agregar estadísticas adicionales
        datos.push([]);
        datos.push(['=== ESTADÍSTICAS ADICIONALES ===']);
        datos.push(['Categorías con equipos:', Object.values(stockData).filter(d => d.total > 0).length]);
        datos.push(['Categorías sin disponibles:', Object.values(stockData).filter(d => d.disponibles === 0).length]);
        datos.push(['Categorías con stock crítico (<5):', Object.values(stockData).filter(d => d.total < 5 && d.total > 0).length]);
        
        // Convertir a CSV
        const csvContent = datos.map(row => 
            row.map(cell => {
                // Escapar comillas y envolver en comillas si contiene comas
                const cellStr = String(cell || '');
                if (cellStr.includes(',') || cellStr.includes('"') || cellStr.includes('\n') || cellStr.includes(';')) {
                    return '"' + cellStr.replace(/"/g, '""') + '"';
                }
                return cellStr;
            }).join(',')
        ).join('\n');
        
        // Crear el archivo y descargarlo
        const BOM = '\uFEFF'; // BOM para caracteres UTF-8
        const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            const nombreArchivo = 'stock_inventario_' + new Date().toISOString().slice(0,10).replace(/-/g, '') + '.csv';
            link.setAttribute('href', url);
            link.setAttribute('download', nombreArchivo);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            
            // Mostrar mensaje de éxito
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Exportación Exitosa!',
                    text: 'El archivo "' + nombreArchivo + '" se ha descargado correctamente',
                    timer: 3000,
                    showConfirmButton: true,
                    confirmButtonText: 'Entendido'
                });
            } else {
                alert('✅ Archivo exportado correctamente: ' + nombreArchivo);
            }
        } else {
            // Fallback para navegadores antiguos
            const dataUrl = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvContent);
            window.open(dataUrl);
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Archivo Generado',
                    text: 'Se ha abierto una nueva ventana con los datos. Guarde el archivo manualmente.',
                    confirmButtonText: 'Entendido'
                });
            } else {
                alert('📄 Archivo generado. Guarde manualmente desde la nueva ventana.');
            }
        }
        
    } catch (error) {
        console.error('Error al exportar:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error en la Exportación',
                text: 'Ocurrió un error al generar el archivo. Inténtelo nuevamente.',
                confirmButtonText: 'Entendido'
            });
        } else {
            alert('❌ Error al exportar: ' + error.message);
        }
    }
}

function actualizarDatos() {
    location.reload();
}

function exportarJSON() {
    try {
        // Crear datos para exportar
        const fecha = new Date().toISOString();
        const exportData = {
            titulo: 'Reporte de Stock por Categoría',
            fechaGeneracion: fecha,
            sistema: 'Sistema de Inventario',
            resumenGeneral: {
                totalEquipos: Object.values(stockData).reduce((sum, data) => sum + data.total, 0),
                activosGeneral: Object.values(stockData).reduce((sum, data) => sum + data.activos, 0),
                disponiblesGeneral: Object.values(stockData).reduce((sum, data) => sum + data.disponibles, 0),
                mantenimientoGeneral: Object.values(stockData).reduce((sum, data) => sum + data.mantenimiento, 0),
                danadosGeneral: Object.values(stockData).reduce((sum, data) => sum + data.danados, 0)
            },
            categorias: Object.keys(stockData).map(key => {
                const data = stockData[key];
                return {
                    id: key,
                    nombre: data.nombre,
                    icono: data.icono,
                    color: data.color,
                    estadisticas: {
                        total: data.total,
                        activos: data.activos,
                        disponibles: data.disponibles,
                        mantenimiento: data.mantenimiento,
                        danados: data.danados,
                        porcentajeUtilizacion: data.total > 0 ? Math.round((data.activos/data.total)*100) : 0,
                        porcentajeDisponibilidad: data.total > 0 ? Math.round((data.disponibles/data.total)*100) : 0
                    },
                    estado: data.total === 0 ? 'Sin Equipos' : 
                           data.disponibles === 0 ? 'Sin Disponibles' :
                           data.total < 5 ? 'Stock Bajo' : 'Normal'
                };
            })
        };
        
        const jsonContent = JSON.stringify(exportData, null, 2);
        const blob = new Blob([jsonContent], { type: 'application/json' });
        const link = document.createElement('a');
        
        const url = URL.createObjectURL(blob);
        const nombreArchivo = 'stock_inventario_' + new Date().toISOString().slice(0,10).replace(/-/g, '') + '.json';
        link.setAttribute('href', url);
        link.setAttribute('download', nombreArchivo);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '¡Exportación JSON Exitosa!',
                text: 'El archivo JSON se ha descargado correctamente',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert('✅ Archivo JSON exportado correctamente');
        }
        
    } catch (error) {
        console.error('Error al exportar JSON:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error en la Exportación JSON',
                text: 'Ocurrió un error al generar el archivo JSON.',
                confirmButtonText: 'Entendido'
            });
        } else {
            alert('❌ Error al exportar JSON: ' + error.message);
        }
    }
}

function imprimirReporte() {
    try {
        const ventanaImpresion = window.open('', '_blank', 'width=800,height=600');
        
        const fecha = new Date().toLocaleDateString('es-ES');
        const hora = new Date().toLocaleTimeString('es-ES');
        
        // Calcular totales
        let totalGeneral = 0;
        let activosGeneral = 0;
        let disponiblesGeneral = 0;
        let mantenimientoGeneral = 0;
        let danadosGeneral = 0;
        
        Object.keys(stockData).forEach(key => {
            const data = stockData[key];
            totalGeneral += data.total;
            activosGeneral += data.activos;
            disponiblesGeneral += data.disponibles;
            mantenimientoGeneral += data.mantenimiento;
            danadosGeneral += data.danados;
        });
        
        // Crear el HTML usando concatenación
        let contenidoHTML = '<!DOCTYPE html>';
        contenidoHTML += '<html><head><meta charset="utf-8">';
        contenidoHTML += '<title>Reporte de Stock - ' + fecha + '</title>';
        contenidoHTML += '<style>';
        contenidoHTML += 'body { font-family: Arial, sans-serif; margin: 20px; }';
        contenidoHTML += '.header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }';
        contenidoHTML += '.tabla { width: 100%; border-collapse: collapse; margin-bottom: 20px; }';
        contenidoHTML += '.tabla th, .tabla td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
        contenidoHTML += '.tabla th { background-color: #343a40; color: white; }';
        contenidoHTML += '.estadistica { display: inline-block; margin: 10px; padding: 10px; background: #e9ecef; border-radius: 5px; }';
        contenidoHTML += '@media print { body { margin: 0; } }';
        contenidoHTML += '</style></head><body>';
        
        // Header
        contenidoHTML += '<div class="header">';
        contenidoHTML += '<h1>📊 REPORTE DE STOCK POR CATEGORÍA</h1>';
        contenidoHTML += '<p>Sistema de Inventario - Generado el ' + fecha + ' a las ' + hora + '</p>';
        contenidoHTML += '</div>';
        
        // Resumen
        contenidoHTML += '<h3>📈 Resumen General</h3>';
        contenidoHTML += '<div class="estadistica"><strong>Total:</strong> ' + totalGeneral + '</div>';
        contenidoHTML += '<div class="estadistica"><strong>En Uso:</strong> ' + activosGeneral + '</div>';
        contenidoHTML += '<div class="estadistica"><strong>Disponibles:</strong> ' + disponiblesGeneral + '</div>';
        contenidoHTML += '<div class="estadistica"><strong>Mantenimiento:</strong> ' + mantenimientoGeneral + '</div>';
        contenidoHTML += '<div class="estadistica"><strong>Dañados:</strong> ' + danadosGeneral + '</div>';
        contenidoHTML += '<div class="estadistica"><strong>Disponibilidad:</strong> ' + (totalGeneral > 0 ? Math.round((disponiblesGeneral/totalGeneral)*100) : 0) + '%</div>';
        
        // Tabla
        contenidoHTML += '<h3>📋 Detalle por Categoría</h3>';
        contenidoHTML += '<table class="tabla"><thead><tr>';
        contenidoHTML += '<th>Categoría</th><th>Total</th><th>En Uso</th><th>Disponibles</th><th>Mantenimiento</th><th>Dañados</th>';
        contenidoHTML += '</tr></thead><tbody>';
        
        Object.keys(stockData).forEach(key => {
            const data = stockData[key];
            contenidoHTML += '<tr>';
            contenidoHTML += '<td>' + data.nombre + '</td>';
            contenidoHTML += '<td>' + data.total + '</td>';
            contenidoHTML += '<td>' + data.activos + '</td>';
            contenidoHTML += '<td>' + data.disponibles + '</td>';
            contenidoHTML += '<td>' + data.mantenimiento + '</td>';
            contenidoHTML += '<td>' + data.danados + '</td>';
            contenidoHTML += '</tr>';
        });
        
        contenidoHTML += '</tbody></table>';
        contenidoHTML += '<script>window.onload = function() { window.print(); }</script>';
        contenidoHTML += '</body></html>';
        
        ventanaImpresion.document.write(contenidoHTML);
        ventanaImpresion.document.close();
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '¡Reporte Generado!',
                text: 'Se abrió la ventana de impresión',
                timer: 2000,
                showConfirmButton: false
            });
        }
        
    } catch (error) {
        console.error('Error al imprimir:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error en la Impresión',
                text: 'Ocurrió un error al generar el reporte.',
                confirmButtonText: 'Entendido'
            });
        } else {
            alert('❌ Error al imprimir: ' + error.message);
        }
    }
}
    const hora = new Date().toLocaleTimeString('es-ES');
    
    let contenidoHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Reporte de Stock - ` + fecha + `</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .resumen { background-color: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
                .tabla { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .tabla th, .tabla td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .tabla th { background-color: #343a40; color: white; }
                .tabla tr:nth-child(even) { background-color: #f2f2f2; }
                .estadistica { display: inline-block; margin: 10px; padding: 10px; background: #e9ecef; border-radius: 5px; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>📊 REPORTE DE STOCK POR CATEGORÍA</h1>
                <p>Sistema de Inventario - Generado el ` + fecha + ` a las ` + hora + `</p>
            </div>
            
            <div class="resumen">
                <h3>📈 Resumen General</h3>
    `;
    
    // Calcular totales
    let totalGeneral = 0;
    let activosGeneral = 0;
    let disponiblesGeneral = 0;
    let mantenimientoGeneral = 0;
    let danadosGeneral = 0;
    
    Object.keys(stockData).forEach(key => {
        const data = stockData[key];
        totalGeneral += data.total;
        activosGeneral += data.activos;
        disponiblesGeneral += data.disponibles;
        mantenimientoGeneral += data.mantenimiento;
        danadosGeneral += data.danados;
    });
    
    contenidoHTML += `
                <div class="estadistica"><strong>En Uso:</strong> \${activosGeneral}</div>
                <div class="estadistica"><strong>Disponibles:</strong> \${disponiblesGeneral}</div>
                <div class="estadistica"><strong>Mantenimiento:</strong> \${mantenimientoGeneral}</div>
                <div class="estadistica"><strong>Dañados:</strong> \${danadosGeneral}</div>
                <div class="estadistica"><strong>Disponibilidad:</strong> \${totalGeneral > 0 ? Math.round((disponiblesGeneral/totalGeneral)*100) : 0}%</div>
            </div>

            <h3>📋 Detalle por Categoría</h3>
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Total</th>
                        <th>En Uso</th>
                        <th>Disponibles</th>
                        <th>Mantenimiento</th>
                        <th>Dañados</th>
                        <th>Utilización %</th>
                        <th>Disponibilidad %</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    Object.keys(stockData).forEach(key => {
        const data = stockData[key];
        let estado = 'Normal';
        if (data.total === 0) estado = 'Sin Equipos';
        else if (data.disponibles === 0) estado = 'Sin Disponibles';
        else if (data.total < 5) estado = 'Stock Bajo';
        
        contenidoHTML += `
                    <tr>
                        <td>\${data.nombre}</td>
                        <td>\${data.total}</td>
                        <td>\${data.activos}</td>
                        <td>\${data.disponibles}</td>
                        <td>\${data.mantenimiento}</td>
                        <td>\${data.danados}</td>
                        <td>\${data.total > 0 ? Math.round((data.activos/data.total)*100) : 0}%</td>
                        <td>\${data.total > 0 ? Math.round((data.disponibles/data.total)*100) : 0}%</td>
                        <td>\${estado}</td>
                    </tr>
        `;
    });
    
    contenidoHTML += `
                </tbody>
            </table>
            
            <div class="footer">
                <p>Este reporte fue generado automáticamente por el Sistema de Inventario</p>
                <p>Fecha y hora de generación: ` + fecha + ` - ` + hora + `</p>
            </div>
            
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </body>
        </html>
    `;
    
    ventanaImpresion.document.write(contenidoHTML);
    ventanaImpresion.document.close();
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Sistema de Stock cargado');
    console.log('📊 Datos de stock:', stockData);
});
JS;

$this->registerJs($script);
?>
