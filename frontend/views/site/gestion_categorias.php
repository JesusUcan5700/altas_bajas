<?php

/** @var yii\web\View $this */

$this->title = 'Gestión por Categorías';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Registrar estilos personalizados
$this->registerCss("
    .equipment-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .equipment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .equipment-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .btn-equipment {
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }
    
    .btn-equipment:hover {
        transform: scale(1.05);
    }
    
    .section-title {
        position: relative;
        text-align: center;
        margin: 3rem 0 2rem 0;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #6f42c1);
        border-radius: 2px;
    }
    
    .hero-section-gestion {
        background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
        color: white;
        border-radius: 20px;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section-gestion::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"rgba(255,255,255,0.1)\"/></svg>') repeat;
        background-size: 50px 50px;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
");
?>

<div class="gestion-categorias">
    <!-- Hero Section -->
    <div class="hero-section-gestion p-5 mb-4">
        <div class="container-fluid py-4 text-center hero-content">
            <h1 class="display-5 fw-bold mb-3">
                <i class="fas fa-cogs me-3"></i>Gestión por Categorías
            </h1>
            <p class="fs-6 fw-light mb-3">Administra y edita equipos existentes por tipo</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="lead">Selecciona una categoría para ver, editar o administrar los equipos de ese tipo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Botón para volver al menú principal -->
        <div class="row mb-4">
            <div class="col-12">
                <?= \yii\helpers\Html::a('<i class="fas fa-arrow-left me-2"></i>Volver al Menú Principal', ['site/index'], [
                    'class' => 'btn btn-secondary'
                ]) ?>
            </div>
        </div>

        <!-- Sección: Gestión por Tipo de Equipo -->
        <h2 class="section-title display-6 fw-bold text-dark">Categorías de Equipos</h2>
        
        <div class="row justify-content-center g-4 mb-5">
            <!-- No Break -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-warning">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-warning">
                            <i class="fas fa-battery-half"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">No Break / UPS</h3>
                        <p class="card-text text-muted mb-4">Gestiona sistemas de alimentación ininterrumpida</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/nobreak-listar']) ?>" class="btn btn-warning btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipos de Cómputo -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-primary">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-primary">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Equipos de Cómputo</h3>
                        <p class="card-text text-muted mb-4">Administra computadoras y equipos informáticos</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/equipo-listar']) ?>" class="btn btn-primary btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Impresoras -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-info">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-info">
                            <i class="fas fa-print"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Impresoras</h3>
                        <p class="card-text text-muted mb-4">Controla impresoras y multifuncionales</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/impresora-listar']) ?>" class="btn btn-info btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monitores -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-success">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-success">
                            <i class="fas fa-tv"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Monitores</h3>
                        <p class="card-text text-muted mb-4">Administra pantallas y displays</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/monitor-listar']) ?>" class="btn btn-success btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adaptadores -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-dark">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-dark">
                            <i class="fas fa-plug"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Adaptadores</h3>
                        <p class="card-text text-muted mb-4">Administra adaptadores y conectores</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/adaptadores-listar']) ?>" class="btn btn-dark btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baterías -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-warning">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-warning">
                            <i class="fas fa-battery-three-quarters"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Baterías</h3>
                        <p class="card-text text-muted mb-4">Gestiona baterías y fuentes de poder</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/baterias-listar']) ?>" class="btn btn-warning btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Almacenamiento -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-info">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-info">
                            <i class="fas fa-hdd"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Almacenamiento</h3>
                        <p class="card-text text-muted mb-4">Administra dispositivos de almacenamiento</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/almacenamiento-listar']) ?>" class="btn btn-info btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Memoria RAM -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-success">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-success">
                            <i class="fas fa-memory"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Memoria RAM</h3>
                        <p class="card-text text-muted mb-4">Gestiona módulos de memoria RAM</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/ram-listar']) ?>" class="btn btn-success btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipo de Sonido -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-danger">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-danger">
                            <i class="fas fa-volume-up"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Equipo de Sonido</h3>
                        <p class="card-text text-muted mb-4">Administra equipos de audio y sonido</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/sonido-listar']) ?>" class="btn btn-danger btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Procesadores -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-warning">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-warning">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Procesadores</h3>
                        <p class="card-text text-muted mb-4">Gestiona procesadores y CPUs</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/procesador-listar']) ?>" class="btn btn-warning btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conectividad -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-primary">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-primary">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Conectividad</h3>
                        <p class="card-text text-muted mb-4">Administra equipos de red y conectividad</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/conectividad-listar']) ?>" class="btn btn-primary btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Telefonía -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-secondary">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-secondary">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Telefonía</h3>
                        <p class="card-text text-muted mb-4">Gestiona equipos de telefonía</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/telefonia-listar']) ?>" class="btn btn-secondary btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Vigilancia -->
            <div class="col-lg-4 col-md-6">
                <div class="card equipment-card h-100 border-dark">
                    <div class="card-body text-center p-4">
                        <div class="equipment-icon text-dark">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3 class="card-title h4 mb-3">Video Vigilancia</h3>
                        <p class="card-text text-muted mb-4">Administra equipos de video vigilancia</p>
                        <div class="d-grid gap-2">
                            <a href="<?= \yii\helpers\Url::to(['site/videovigilancia-listar']) ?>" class="btn btn-dark btn-equipment">
                                <i class="fas fa-list me-2"></i>Ver / Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
