<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Equipo */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Agregar Equipo de Cómputo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-desktop me-2"></i><?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>¡Éxito!</strong> <?= Yii::$app->session->getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>¡Error!</strong> <?= Yii::$app->session->getFlash('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
            <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'CPU')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'DD')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                
                <!-- Campo DD2 con checkbox -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="tiene-dd2" onchange="toggleDD2()">
                            <label class="form-check-label" for="tiene-dd2">
                                <i class="fas fa-hdd me-2"></i>Este equipo tiene segundo disco duro
                            </label>
                        </div>
                        <div id="dd2-field" style="display: none;">
                            <?= $form->field($model, 'DD2')->textInput([
                                'maxlength' => true,
                                'id' => 'equipo-dd2',
                                'placeholder' => 'Ej: 1TB HDD, 512GB SSD',
                                'disabled' => true
                            ]) ?>
                        </div>
                        
                        <!-- DD3 aparece solo si DD2 está activado -->
                        <div id="dd3-container" style="display: none;">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="tiene-dd3" onchange="toggleDD3()">
                                <label class="form-check-label" for="tiene-dd3">
                                    <i class="fas fa-hdd me-2"></i>Este equipo tiene tercer disco duro
                                </label>
                            </div>
                            <div id="dd3-field" style="display: none;">
                                <?= $form->field($model, 'DD3')->textInput([
                                    'maxlength' => true,
                                    'id' => 'equipo-dd3',
                                    'placeholder' => 'Ej: 2TB HDD, 1TB SSD',
                                    'disabled' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <!-- DD4 aparece solo si DD3 está activado -->
                        <div id="dd4-container" style="display: none;">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="tiene-dd4" onchange="toggleDD4()">
                                <label class="form-check-label" for="tiene-dd4">
                                    <i class="fas fa-hdd me-2"></i>Este equipo tiene cuarto disco duro
                                </label>
                            </div>
                            <div id="dd4-field" style="display: none;">
                                <?= $form->field($model, 'DD4')->textInput([
                                    'maxlength' => true,
                                    'id' => 'equipo-dd4',
                                    'placeholder' => 'Ej: 500GB SSD, 4TB HDD',
                                    'disabled' => true
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'RAM')->textInput(['maxlength' => true]) ?>
                        
                        <!-- Campo RAM2 con checkbox -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="tiene-ram2" onchange="toggleRAM2()">
                            <label class="form-check-label" for="tiene-ram2">
                                <i class="fas fa-memory me-2"></i>Este equipo tiene segunda RAM
                            </label>
                        </div>
                        <div id="ram2-field" style="display: none;">
                            <?= $form->field($model, 'RAM2')->textInput([
                                'maxlength' => true,
                                'id' => 'equipo-ram2',
                                'placeholder' => 'Ej: 8GB DDR4, 16GB DDR3',
                                'disabled' => true
                            ]) ?>
                        </div>
                        
                        <!-- RAM3 aparece solo si RAM2 está activado -->
                        <div id="ram3-container" style="display: none;">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="tiene-ram3" onchange="toggleRAM3()">
                                <label class="form-check-label" for="tiene-ram3">
                                    <i class="fas fa-memory me-2"></i>Este equipo tiene tercera RAM
                                </label>
                            </div>
                            <div id="ram3-field" style="display: none;">
                                <?= $form->field($model, 'RAM3')->textInput([
                                    'maxlength' => true,
                                    'id' => 'equipo-ram3',
                                    'placeholder' => 'Ej: 4GB DDR4, 8GB DDR3',
                                    'disabled' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <!-- RAM4 aparece solo si RAM3 está activado -->
                        <div id="ram4-container" style="display: none;">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="tiene-ram4" onchange="toggleRAM4()">
                                <label class="form-check-label" for="tiene-ram4">
                                    <i class="fas fa-memory me-2"></i>Este equipo tiene cuarta RAM
                                </label>
                            </div>
                            <div id="ram4-field" style="display: none;">
                                <?= $form->field($model, 'RAM4')->textInput([
                                    'maxlength' => true,
                                    'id' => 'equipo-ram4',
                                    'placeholder' => 'Ej: 2GB DDR3, 4GB DDR4',
                                    'disabled' => true
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'MARCA')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'MODELO')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'NUM_SERIE')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'NUM_INVENTARIO')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'EMISION_INVENTARIO')->input('date', [
                            'value' => $model->isNewRecord ? date('Y-m-d') : $model->EMISION_INVENTARIO
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'Estado')->dropDownList(frontend\models\Equipo::getEstados(), ['prompt' => 'Selecciona Estado']) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'tipoequipo')->dropDownList(frontend\models\Equipo::getTipos(), [
                            'prompt' => 'Selecciona Tipo de Equipo',
                            'id' => 'equipo-tipoequipo',
                            'onchange' => 'toggleTipoEquipo()'
                        ]) ?>
                        
                        <!-- Campo de texto que aparece cuando se selecciona "Otro" -->
                        <div id="tipo-texto-container" style="display: none;">
                            <?= $form->field($model, 'tipoequipo', ['template' => '{label}{input}{error}'])->textInput([
                                'maxlength' => true,
                                'id' => 'equipo-tipoequipo-texto',
                                'placeholder' => 'Especifica el tipo de equipo',
                                'style' => 'display: none;'
                            ])->label('Especificar Tipo de Equipo') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'ubicacion_edificio')->dropDownList(frontend\models\Equipo::getUbicacionesEdificio(), ['prompt' => 'Selecciona Edificio']) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $form->field($model, 'ubicacion_detalle')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <?= $form->field($model, 'descripcion')->textarea(['rows' => 3]) ?>
                    </div>
                </div>
                    <div class="form-group text-center mt-4">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success btn-lg me-2']) ?>
                        <?= Html::a('<i class="fas fa-arrow-left me-2"></i>Volver a Agregar Nuevo', ['site/agregar-nuevo'], ['class' => 'btn btn-secondary btn-lg me-2']) ?>
                        <?= Html::a('<i class="fas fa-home me-2"></i>Menú Principal', ['site/index'], ['class' => 'btn btn-outline-secondary btn-lg']) ?>
                    </div>
            <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones para activar/desactivar DD2, DD3 y DD4 en cascada
function toggleDD2() {
    const checkbox = document.getElementById('tiene-dd2');
    const dd2Field = document.getElementById('dd2-field');
    const dd2Input = document.getElementById('equipo-dd2');
    const dd3Container = document.getElementById('dd3-container');
    
    if (checkbox.checked) {
        // Activar DD2
        dd2Field.style.display = 'block';
        dd2Input.disabled = false;
        dd2Input.value = '';
        dd2Input.focus();
        
        // Mostrar la opción para DD3
        dd3Container.style.display = 'block';
    } else {
        // Desactivar DD2
        dd2Field.style.display = 'none';
        dd2Input.disabled = true;
        dd2Input.value = 'NO';
        
        // Ocultar y desactivar DD3 y DD4
        dd3Container.style.display = 'none';
        const dd3Checkbox = document.getElementById('tiene-dd3');
        if (dd3Checkbox.checked) {
            dd3Checkbox.checked = false;
            toggleDD3();
        }
    }
}

function toggleDD3() {
    const checkbox = document.getElementById('tiene-dd3');
    const dd3Field = document.getElementById('dd3-field');
    const dd3Input = document.getElementById('equipo-dd3');
    const dd4Container = document.getElementById('dd4-container');
    
    if (checkbox.checked) {
        // Activar DD3
        dd3Field.style.display = 'block';
        dd3Input.disabled = false;
        dd3Input.value = '';
        dd3Input.focus();
        
        // Mostrar la opción para DD4
        dd4Container.style.display = 'block';
    } else {
        // Desactivar DD3
        dd3Field.style.display = 'none';
        dd3Input.disabled = true;
        dd3Input.value = 'NO';
        
        // Ocultar y desactivar DD4
        dd4Container.style.display = 'none';
        const dd4Checkbox = document.getElementById('tiene-dd4');
        if (dd4Checkbox.checked) {
            dd4Checkbox.checked = false;
            toggleDD4();
        }
    }
}

function toggleDD4() {
    const checkbox = document.getElementById('tiene-dd4');
    const dd4Field = document.getElementById('dd4-field');
    const dd4Input = document.getElementById('equipo-dd4');
    
    if (checkbox.checked) {
        // Activar DD4
        dd4Field.style.display = 'block';
        dd4Input.disabled = false;
        dd4Input.value = '';
        dd4Input.focus();
    } else {
        // Desactivar DD4
        dd4Field.style.display = 'none';
        dd4Input.disabled = true;
        dd4Input.value = 'NO';
    }
}

function toggleTipoEquipo() {
    const tipoSelect = document.getElementById('equipo-tipoequipo');
    const tipoTextoContainer = document.getElementById('tipo-texto-container');
    const tipoTextoInput = document.getElementById('equipo-tipoequipo-texto');
    
    if (tipoSelect.value === 'Otro') {
        // Ocultar dropdown y mostrar campo de texto
        tipoSelect.style.display = 'none';
        tipoTextoContainer.style.display = 'block';
        tipoTextoInput.style.display = 'block';
        tipoTextoInput.focus();
        
        // Agregar botón para volver al dropdown
        if (!document.getElementById('btn-volver-dropdown')) {
            const btnVolver = document.createElement('button');
            btnVolver.type = 'button';
            btnVolver.id = 'btn-volver-dropdown';
            btnVolver.className = 'btn btn-sm btn-outline-secondary mt-2';
            btnVolver.innerHTML = '<i class="fas fa-arrow-left"></i> Volver a opciones';
            btnVolver.onclick = volverADropdown;
            tipoTextoContainer.appendChild(btnVolver);
        }
    }
}

function volverADropdown() {
    const tipoSelect = document.getElementById('equipo-tipoequipo');
    const tipoTextoContainer = document.getElementById('tipo-texto-container');
    const tipoTextoInput = document.getElementById('equipo-tipoequipo-texto');
    const btnVolver = document.getElementById('btn-volver-dropdown');
    
    // Mostrar dropdown y ocultar campo de texto
    tipoSelect.style.display = 'block';
    tipoTextoContainer.style.display = 'none';
    tipoTextoInput.style.display = 'none';
    tipoSelect.value = '';
    tipoTextoInput.value = '';
    
    // Quitar el botón
    if (btnVolver) {
        btnVolver.remove();
    }
}

// Funciones para activar/desactivar RAM2, RAM3 y RAM4 en cascada
function toggleRAM2() {
    const checkbox = document.getElementById('tiene-ram2');
    const ram2Field = document.getElementById('ram2-field');
    const ram2Input = document.getElementById('equipo-ram2');
    const ram3Container = document.getElementById('ram3-container');
    
    if (checkbox.checked) {
        // Activar RAM2
        ram2Field.style.display = 'block';
        ram2Input.disabled = false;
        ram2Input.value = '';
        ram2Input.focus();
        
        // Mostrar la opción para RAM3
        ram3Container.style.display = 'block';
    } else {
        // Desactivar RAM2
        ram2Field.style.display = 'none';
        ram2Input.disabled = true;
        ram2Input.value = 'NO';
        
        // Ocultar y desactivar RAM3 y RAM4
        ram3Container.style.display = 'none';
        const ram3Checkbox = document.getElementById('tiene-ram3');
        if (ram3Checkbox.checked) {
            ram3Checkbox.checked = false;
            toggleRAM3();
        }
    }
}

function toggleRAM3() {
    const checkbox = document.getElementById('tiene-ram3');
    const ram3Field = document.getElementById('ram3-field');
    const ram3Input = document.getElementById('equipo-ram3');
    const ram4Container = document.getElementById('ram4-container');
    
    if (checkbox.checked) {
        // Activar RAM3
        ram3Field.style.display = 'block';
        ram3Input.disabled = false;
        ram3Input.value = '';
        ram3Input.focus();
        
        // Mostrar la opción para RAM4
        ram4Container.style.display = 'block';
    } else {
        // Desactivar RAM3
        ram3Field.style.display = 'none';
        ram3Input.disabled = true;
        ram3Input.value = 'NO';
        
        // Ocultar y desactivar RAM4
        ram4Container.style.display = 'none';
        const ram4Checkbox = document.getElementById('tiene-ram4');
        if (ram4Checkbox.checked) {
            ram4Checkbox.checked = false;
            toggleRAM4();
        }
    }
}

function toggleRAM4() {
    const checkbox = document.getElementById('tiene-ram4');
    const ram4Field = document.getElementById('ram4-field');
    const ram4Input = document.getElementById('equipo-ram4');
    
    if (checkbox.checked) {
        // Activar RAM4
        ram4Field.style.display = 'block';
        ram4Input.disabled = false;
        ram4Input.value = '';
        ram4Input.focus();
    } else {
        // Desactivar RAM4
        ram4Field.style.display = 'none';
        ram4Input.disabled = true;
        ram4Input.value = 'NO';
    }
}

// Inicializar todos los campos DD y RAM como "NO" cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('equipo-dd2').value = 'NO';
    document.getElementById('equipo-dd3').value = 'NO';
    document.getElementById('equipo-dd4').value = 'NO';
    document.getElementById('equipo-ram2').value = 'NO';
    document.getElementById('equipo-ram3').value = 'NO';
    document.getElementById('equipo-ram4').value = 'NO';
    
    // Asegurar que todos los campos DD y RAM tengan el valor correcto antes de enviar
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const dd2Checkbox = document.getElementById('tiene-dd2');
        const dd3Checkbox = document.getElementById('tiene-dd3');
        const dd4Checkbox = document.getElementById('tiene-dd4');
        const ram2Checkbox = document.getElementById('tiene-ram2');
        const ram3Checkbox = document.getElementById('tiene-ram3');
        const ram4Checkbox = document.getElementById('tiene-ram4');
        
        const dd2Input = document.getElementById('equipo-dd2');
        const dd3Input = document.getElementById('equipo-dd3');
        const dd4Input = document.getElementById('equipo-dd4');
        const ram2Input = document.getElementById('equipo-ram2');
        const ram3Input = document.getElementById('equipo-ram3');
        const ram4Input = document.getElementById('equipo-ram4');
        const tipoTextoInput = document.getElementById('equipo-tipoequipo-texto');
        const tipoSelect = document.getElementById('equipo-tipoequipo');
        
        // Habilitar temporalmente todos los campos para enviar valores
        dd2Input.disabled = false;
        dd3Input.disabled = false;
        dd4Input.disabled = false;
        ram2Input.disabled = false;
        ram3Input.disabled = false;
        ram4Input.disabled = false;
        
        // Asignar valores según el estado de los checkboxes DD
        if (!dd2Checkbox.checked) {
            dd2Input.value = 'NO';
        }
        if (!dd3Checkbox.checked) {
            dd3Input.value = 'NO';
        }
        if (!dd4Checkbox.checked) {
            dd4Input.value = 'NO';
        }
        
        // Asignar valores según el estado de los checkboxes RAM
        if (!ram2Checkbox.checked) {
            ram2Input.value = 'NO';
        }
        if (!ram3Checkbox.checked) {
            ram3Input.value = 'NO';
        }
        if (!ram4Checkbox.checked) {
            ram4Input.value = 'NO';
        }
        
        // Si el campo de texto está visible, usar su valor en lugar del dropdown
        if (tipoTextoInput.style.display !== 'none' && tipoTextoInput.value.trim() !== '') {
            // Crear un input oculto para enviar el valor personalizado
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'Equipo[tipoequipo]';
            hiddenInput.value = tipoTextoInput.value;
            form.appendChild(hiddenInput);
            
            // Deshabilitar el dropdown para que no se envíe
            tipoSelect.disabled = true;
        }
    });
});
</script>
