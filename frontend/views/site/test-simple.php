<?php

/** @var yii\web\View $this */

$this->title = 'Ver Equipos - Versión Simple';
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>📋 Ver Equipos No Break - Versión Simple</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>DEBUG:</strong> Versión simplificada para mostrar solo datos - <?= date('Y-m-d H:i:s') ?>
            </div>
            
            <button class="btn btn-primary mb-3" onclick="cargarDatos()">🔄 Cargar Datos</button>
            
            <div id="resultado" class="mt-3">
                <p>Haz clic en "Cargar Datos" para ver la información...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Función simple para cargar datos
function cargarDatos() {
    const resultadoDiv = document.getElementById('resultado');
    
    resultadoDiv.innerHTML = '<p>⏳ Cargando datos...</p>';
    
    fetch('<?= \yii\helpers\Url::to(['site/editar']) ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'action=cargar_equipos'
    })
    .then(response => {
        console.log('📡 Respuesta recibida:', response);
        console.log('📊 Status:', response.status);
        console.log('📝 Status Text:', response.statusText);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }
        
        return response.text(); // Primero como texto para ver qué llega
    })
    .then(texto => {
        console.log('📄 Texto recibido:', texto);
        
        try {
            const datos = JSON.parse(texto);
            console.log('✅ JSON parseado:', datos);
            mostrarDatos(datos);
        } catch (e) {
            console.error('❌ Error parseando JSON:', e);
            resultadoDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h5>❌ Error de formato JSON</h5>
                    <p><strong>Error:</strong> ${e.message}</p>
                    <hr>
                    <h6>Respuesta del servidor:</h6>
                    <pre style="max-height: 300px; overflow: auto;">${texto}</pre>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('🚨 Error completo:', error);
        resultadoDiv.innerHTML = `
            <div class="alert alert-danger">
                <h5>🚨 Error de conexión</h5>
                <p><strong>Mensaje:</strong> ${error.message}</p>
                <p><strong>Tipo:</strong> ${error.name}</p>
                <hr>
                <p>📋 <strong>Pasos para resolver:</strong></p>
                <ol>
                    <li>Verifica que el servidor web esté funcionando</li>
                    <li>Revisa la consola del navegador (F12)</li>
                    <li>Confirma que la URL sea correcta</li>
                </ol>
            </div>
        `;
    });
}

function mostrarDatos(datos) {
    const resultadoDiv = document.getElementById('resultado');
    
    console.log('🔍 Analizando datos:', datos);
    console.log('📝 Tipo:', typeof datos);
    console.log('📊 Es array:', Array.isArray(datos));
    
    if (datos.error) {
        resultadoDiv.innerHTML = `
            <div class="alert alert-warning">
                <h5>⚠️ Error del servidor</h5>
                <p><strong>Mensaje:</strong> ${datos.message}</p>
                ${datos.details ? `
                    <hr>
                    <h6>Detalles técnicos:</h6>
                    <ul>
                        <li><strong>Tipo:</strong> ${datos.details.tipo_error || 'N/A'}</li>
                        <li><strong>Código:</strong> ${datos.details.codigo_error || 'N/A'}</li>
                        <li><strong>Archivo:</strong> ${datos.details.archivo || 'N/A'}</li>
                        <li><strong>Línea:</strong> ${datos.details.linea || 'N/A'}</li>
                    </ul>
                ` : ''}
                ${datos.sugerencia ? `<p><strong>💡 Sugerencia:</strong> ${datos.sugerencia}</p>` : ''}
            </div>
        `;
        return;
    }
    
    if (!Array.isArray(datos)) {
        resultadoDiv.innerHTML = `
            <div class="alert alert-info">
                <h5>ℹ️ Datos recibidos (no es array)</h5>
                <pre>${JSON.stringify(datos, null, 2)}</pre>
            </div>
        `;
        return;
    }
    
    if (datos.length === 0) {
        resultadoDiv.innerHTML = `
            <div class="alert alert-info">
                <h5>📭 Sin datos</h5>
                <p>No se encontraron equipos en la base de datos.</p>
            </div>
        `;
        return;
    }
    
    // Mostrar datos en formato simple
    let html = `
        <div class="alert alert-success">
            <h5>✅ Datos cargados correctamente</h5>
            <p><strong>Total de equipos:</strong> ${datos.length}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Estado</th>
                        <th>N° Serie</th>
                        <th>Ubicación</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    datos.forEach((equipo, index) => {
        console.log(`📦 Equipo ${index + 1}:`, equipo);
        
        const id = equipo.id || 'N/A';
        const marca = equipo.data?.MARCA || equipo.marca || 'N/A';
        const modelo = equipo.data?.MODELO || equipo.modelo || 'N/A';
        const estado = equipo.data?.Estado || equipo.estado || 'N/A';
        const serie = equipo.data?.NUMERO_SERIE || equipo.numero_serie || 'N/A';
        const ubicacion = equipo.ubicacion || 'N/A';
        
        const badgeClass = estado === 'Activo' ? 'bg-success' : 
                          estado === 'Reparación' ? 'bg-warning' : 
                          estado === 'Inactivo' ? 'bg-secondary' : 'bg-dark';
        
        html += `
            <tr>
                <td><strong>${id}</strong></td>
                <td>${marca}</td>
                <td>${modelo}</td>
                <td><span class="badge ${badgeClass}">${estado}</span></td>
                <td>${serie}</td>
                <td>${ubicacion}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <h6>🔍 Datos raw del primer equipo:</h6>
            <pre style="max-height: 200px; overflow: auto;">${JSON.stringify(datos[0], null, 2)}</pre>
        </div>
    `;
    
    resultadoDiv.innerHTML = html;
}

// Auto-cargar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Página cargada, iniciando carga automática de datos...');
    cargarDatos();
});
</script>
