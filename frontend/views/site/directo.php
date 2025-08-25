<?php

/** @var yii\web\View $this */

$this->title = 'Equipos No Break - Directo';

// Obtener datos directamente en PHP
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
        <div class="card-header bg-success text-white">
            <h3>📋 Equipos No Break - Directo desde PHP</h3>
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
                    <h5>✅ Datos cargados</h5>
                    <p><strong>Total de equipos:</strong> <?= count($equipos) ?></p>
                </div>
                
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Capacidad</th>
                            <th>Serie</th>
                            <th>Estado</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipos as $equipo): ?>
                            <?php
                                $estado = $equipo['Estado'] ?? '';
                                $estadoLower = strtolower($estado);
                                
                                $colorEstado = 'text-secondary';
                                if ($estadoLower === 'activo') $colorEstado = 'text-success';
                                elseif ($estadoLower === 'dañado') $colorEstado = 'text-danger';
                                elseif ($estadoLower === 'reparacion') $colorEstado = 'text-warning';
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($equipo['idNOBREAK'] ?? 'N/A') ?></strong></td>
                                <td><?= htmlspecialchars($equipo['MARCA'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($equipo['MODELO'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($equipo['CAPACIDAD'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($equipo['NUMERO_SERIE'] ?? 'N/A') ?></td>
                                <td><span class="<?= $colorEstado ?>"><strong><?= htmlspecialchars($estado) ?></strong></span></td>
                                <td>
                                    <?= htmlspecialchars($equipo['ubicacion_edificio'] ?? 'N/A') ?> - 
                                    <?= htmlspecialchars($equipo['ubicacion_detalle'] ?? 'N/A') ?>
                                </td>
                                <td><?= htmlspecialchars($equipo['fecha'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Mostrar datos raw del primer equipo para debug -->
                <div class="mt-4">
                    <h6>🔍 Datos raw del primer equipo:</h6>
                    <pre class="bg-light p-3" style="max-height: 200px; overflow: auto;"><?= htmlspecialchars(print_r($equipos[0], true)) ?></pre>
                </div>
            <?php endif; ?>
            
            <div class="mt-3">
                <small class="text-muted">
                    📊 Información de conexión:<br>
                    <strong>DSN:</strong> <?= htmlspecialchars(Yii::$app->db->dsn) ?><br>
                    <strong>Fecha:</strong> <?= date('Y-m-d H:i:s') ?>
                </small>
            </div>
        </div>
    </div>
</div>
