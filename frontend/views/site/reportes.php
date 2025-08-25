<?php

/** @var yii\web\View $this */

$this->title = 'Reportes del Sistema';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Agregar estilos
$this->registerCss("
    .reports-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    
    .reports-card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 15px;
    }
    
    .btn-report {
        border-radius: 20px;
        padding: 12px 30px;
        font-weight: 500;
    }
    
    .report-option {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 2px solid #e9ecef;
    }
    
    .report-option:hover {
        border-color: #dc3545;
        background-color: #f8f9fa;
    }
");
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card reports-card">
                <div class="card-header reports-header text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Reportes del Sistema
                    </h3>
                    <p class="mb-0 mt-2">Genera informes y estadísticas de equipos</p>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-warning">
                        <i class="fas fa-construction me-2"></i>
                        <strong>Sección en desarrollo</strong>
                        <br>Los reportes están siendo implementados. Próximamente podrás generar informes detallados.
                    </div>
                    
                    <div class="row">
                        <!-- Reporte General -->
                        <div class="col-md-6 mb-4">
                            <div class="p-4 report-option">
                                <h5><i class="fas fa-chart-pie text-primary me-2"></i>Reporte General</h5>
                                <p class="text-muted">Resumen estadístico de todos los equipos por categoría y estado</p>
                                <button class="btn btn-outline-primary btn-report" onclick="alert('Reporte en desarrollo')">
                                    <i class="fas fa-download me-2"></i>Generar PDF
                                </button>
                            </div>
                        </div>
                        
                        <!-- Inventario por Categoría -->
                        <div class="col-md-6 mb-4">
                            <div class="p-4 report-option">
                                <h5><i class="fas fa-list-alt text-success me-2"></i>Inventario por Categoría</h5>
                                <p class="text-muted">Listado detallado de equipos separado por tipo</p>
                                <button class="btn btn-outline-success btn-report" onclick="alert('Reporte en desarrollo')">
                                    <i class="fas fa-download me-2"></i>Generar Excel
                                </button>
                            </div>
                        </div>
                        
                        <!-- Equipos por Estado -->
                        <div class="col-md-6 mb-4">
                            <div class="p-4 report-option">
                                <h5><i class="fas fa-exclamation-triangle text-warning me-2"></i>Equipos por Estado</h5>
                                <p class="text-muted">Reporte de equipos activos, inactivos y en reparación</p>
                                <button class="btn btn-outline-warning btn-report" onclick="alert('Reporte en desarrollo')">
                                    <i class="fas fa-download me-2"></i>Generar PDF
                                </button>
                            </div>
                        </div>
                        
                        <!-- Equipos por Ubicación -->
                        <div class="col-md-6 mb-4">
                            <div class="p-4 report-option">
                                <h5><i class="fas fa-map-marker-alt text-info me-2"></i>Equipos por Ubicación</h5>
                                <p class="text-muted">Distribución de equipos por edificio y ubicación</p>
                                <button class="btn btn-outline-info btn-report" onclick="alert('Reporte en desarrollo')">
                                    <i class="fas fa-download me-2"></i>Generar Excel
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estadísticas rápidas -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Estadísticas Rápidas</h5>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <i class="fas fa-battery-half fa-2x text-warning mb-2"></i>
                                    <h4 class="text-primary">-</h4>
                                    <small class="text-muted">No Break</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <i class="fas fa-desktop fa-2x text-primary mb-2"></i>
                                    <h4 class="text-primary">-</h4>
                                    <small class="text-muted">Equipos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <i class="fas fa-print fa-2x text-info mb-2"></i>
                                    <h4 class="text-primary">-</h4>
                                    <small class="text-muted">Impresoras</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <i class="fas fa-tv fa-2x text-success mb-2"></i>
                                    <h4 class="text-primary">-</h4>
                                    <small class="text-muted">Monitores</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="btn btn-secondary btn-report">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Menú
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
