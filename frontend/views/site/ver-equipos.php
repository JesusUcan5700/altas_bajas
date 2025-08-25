<?php

/** @var yii\web\View $this */

$this->title = 'Ver Equipos Disponibles';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Cargar datos directamente en PHP
try {
    $connection = Yii::$app->db;
    $sql = "SELECT * FROM nobreak ORDER BY idNOBREAK ASC";
    $equipos = $connection->createCommand($sql)->queryAll();
    $error = null;
} catch (Exception $e) {
    $equipos = [];
    $error = $e->getMessage();
}
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3><i class="fas fa-list me-2"></i>Equipos Disponibles</h3>
        </div>
        <div class="card-body">
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <h5>❌ Error de base de datos</h5>
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php elseif (empty($equipos)): ?>
                <div class="alert alert-info">
                    <h5>📭 Sin equipos</h5>
                    <p>No hay equipos registrados en la base de datos.</p>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    <h5>✅ Equipos encontrados</h5>
                    <p><strong>Total:</strong> <?= count($equipos) ?> equipos disponibles</p>
                </div>
                
                <div class="row">
                    <?php foreach ($equipos as $equipo): ?>
                        <?php
                            $estado = $equipo['Estado'] ?? '';
                            $estadoLower = strtolower($estado);
                            
                            $colorCard = 'border-success';
                            $colorBadge = 'bg-success';
                            $icono = 'fas fa-check-circle';
                            
                            if ($estadoLower === 'reparación' || $estadoLower === 'reparacion') {
                                $colorCard = 'border-warning';
                                $colorBadge = 'bg-warning';
                                $icono = 'fas fa-tools';
                            } elseif ($estadoLower === 'inactivo' || $estadoLower === 'dañado') {
                                $colorCard = 'border-danger';
                                $colorBadge = 'bg-danger';
                                $icono = 'fas fa-times-circle';
                            } elseif ($estadoLower === 'baja') {
                                $colorCard = 'border-secondary';
                                $colorBadge = 'bg-secondary';
                                $icono = 'fas fa-archive';
                            }
                        ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 <?= $colorCard ?>">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="<?= $icono ?> me-2"></i>
                                        <strong>ID: <?= htmlspecialchars($equipo['idNOBREAK'] ?? 'N/A') ?></strong>
                                        <span class="badge <?= $colorBadge ?> float-end"><?= htmlspecialchars($estado) ?></span>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($equipo['MARCA'] ?? 'Sin marca') ?></h5>
                                    <p class="card-text">
                                        <strong>Modelo:</strong> <?= htmlspecialchars($equipo['MODELO'] ?? 'N/A') ?><br>
                                        <strong>Capacidad:</strong> <?= htmlspecialchars($equipo['CAPACIDAD'] ?? 'N/A') ?><br>
                                        <strong>Serie:</strong> <?= htmlspecialchars($equipo['NUMERO_SERIE'] ?? 'N/A') ?><br>
                                        <strong>Inventario:</strong> <?= htmlspecialchars($equipo['NUMERO_INVENTARIO'] ?? 'N/A') ?>
                                    </p>
                                    
                                    <?php if (!empty($equipo['ubicacion_edificio']) || !empty($equipo['ubicacion_detalle'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?= htmlspecialchars($equipo['ubicacion_edificio'] ?? '') ?>
                                                <?php if (!empty($equipo['ubicacion_edificio']) && !empty($equipo['ubicacion_detalle'])): ?>
                                                    -
                                                <?php endif; ?>
                                                <?= htmlspecialchars($equipo['ubicacion_detalle'] ?? '') ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($equipo['DESCRIPCION'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Descripción:</strong><br>
                                                <?= htmlspecialchars(substr($equipo['DESCRIPCION'], 0, 100)) ?>
                                                <?php if (strlen($equipo['DESCRIPCION']) > 100): ?>...<?php endif; ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i>
                                        <?= $equipo['fecha'] ? date('d/m/Y', strtotime($equipo['fecha'])) : 'Sin fecha' ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <?= \yii\helpers\Html::a('<i class="fas fa-arrow-left"></i> Volver al Menú', ['site/index'], [
                    'class' => 'btn btn-secondary me-2'
                ]) ?>
                <?= \yii\helpers\Html::a('<i class="fas fa-plus"></i> Agregar Nuevo', ['site/agregar-nuevo'], [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>
        </div>
    </div>
</div>
