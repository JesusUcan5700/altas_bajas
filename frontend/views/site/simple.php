<?php

/** @var yii\web\View $this */

$this->title = 'Equipos No Break - Simple';
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>📋 Equipos No Break</h3>
        </div>
        <div class="card-body">
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
                <tbody id="tabla-equipos">
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="fas fa-spinner fa-spin"></i> Cargando equipos...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Función ultra simple para cargar equipos
function cargarEquipos() {
    const tbody = document.getElementById('tabla-equipos');
    
    fetch('<?= \yii\helpers\Url::to(['site/simple']) ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: ''
    })
    .then(response => {
        console.log('📡 Respuesta:', response.status, response.statusText);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.text(); // Primero como texto para debug
    })
    .then(texto => {
        console.log('📄 Respuesta como texto:', texto);
        
        try {
            const equipos = JSON.parse(texto);
            console.log('✅ JSON parseado:', equipos);
            mostrarEquipos(equipos);
        } catch (e) {
            console.error('❌ Error parseando JSON:', e);
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        ❌ Error: Respuesta no es JSON válido<br>
                        <small>Respuesta del servidor: ${texto.substring(0, 200)}...</small>
                    </td>
                </tr>
            `;
        }
    })
    .catch(error => {
        console.error('🚨 Error:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-danger">
                    🚨 Error: ${error.message}
                </td>
            </tr>
        `;
    });
}

function mostrarEquipos(equipos) {
    const tbody = document.getElementById('tabla-equipos');
    
    if (equipos.error) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-danger">
                    ❌ Error: ${equipos.message}
                </td>
            </tr>
        `;
        return;
    }
    
    if (!Array.isArray(equipos) || equipos.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-muted">
                    📭 No hay equipos registrados
                </td>
            </tr>
        `;
        return;
    }
    
    // Limpiar tabla
    tbody.innerHTML = '';
    
    // Agregar cada equipo
    equipos.forEach(equipo => {
        const estado = equipo.estado || '';
        const estadoLower = estado.toLowerCase();
        
        let colorEstado = '';
        if (estadoLower === 'activo') colorEstado = 'text-success';
        else if (estadoLower === 'dañado') colorEstado = 'text-danger';
        else if (estadoLower === 'reparacion') colorEstado = 'text-warning';
        else colorEstado = 'text-secondary';
        
        const fila = `
            <tr>
                <td><strong>${equipo.id || 'N/A'}</strong></td>
                <td>${equipo.marca || 'N/A'}</td>
                <td>${equipo.modelo || 'N/A'}</td>
                <td>${equipo.capacidad || 'N/A'}</td>
                <td>${equipo.numero_serie || 'N/A'}</td>
                <td><span class="${colorEstado}"><strong>${estado}</strong></span></td>
                <td>${equipo.ubicacion_edificio || 'N/A'} - ${equipo.ubicacion_detalle || 'N/A'}</td>
                <td>${equipo.fecha || 'N/A'}</td>
            </tr>
        `;
        
        tbody.innerHTML += fila;
    });
    
    console.log(`✅ Se mostraron ${equipos.length} equipos`);
}

// Cargar equipos automáticamente
document.addEventListener('DOMContentLoaded', cargarEquipos);
</script>
