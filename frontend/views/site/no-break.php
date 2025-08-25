<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/** @var yii\web\View $this */
/** @var frontend\models\Nobreak $model */
$this->title = 'Agregar Nuevo No Break';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-battery-full me-2"></i><?= Html::encode($this->title) ?>
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
                        <div class="col-md-6">
                            <?= $form->field($model, 'MARCA')->dropDownList([
                                '' => 'Selecciona una marca',
                                'APC' => 'APC',
                                'Tripp Lite' => 'Tripp Lite',
                                'CyberPower' => 'CyberPower',
                                'Eaton' => 'Eaton',
                                'Forza' => 'Forza',
                                'Schneider Electric' => 'Schneider Electric',
                                'Vertiv' => 'Vertiv',
                                'Otra' => 'Otra',
                            ], ['class' => 'form-select']) ?>
                            <?= $form->field($model, 'MODELO')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                            <?= $form->field($model, 'CAPACIDAD')->textInput(['maxlength' => 45, 'placeholder' => 'Ej: 1500VA/900W', 'class' => 'form-control']) ?>
                            <?= $form->field($model, 'NUMERO_SERIE')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                            <?= $form->field($model, 'NUMERO_INVENTARIO')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Estado')->dropDownList([
                                '' => 'Selecciona un estado',
                                'Activo' => 'Activo',
                                'Inactivo' => 'Inactivo',
                                'Baja' => 'Baja',
                                'Reparación' => 'Reparación',
                            ], ['class' => 'form-select']) ?>
                            <?= $form->field($model, 'fecha')->input('date', [
                                'value' => date('Y-m-d'),
                                'class' => 'form-control'
                            ]) ?>
                            <?= $form->field($model, 'ubicacion_edificio')->dropDownList([
                                '' => 'Selecciona un edificio',
                                'Edificio A' => 'Edificio A',
                                'Edificio B' => 'Edificio B',
                                'Edificio C' => 'Edificio C',
                                'Edificio D' => 'Edificio D',
                                'Edificio E' => 'Edificio E',
                                'Edificio F' => 'Edificio F',
                                'Edificio G' => 'Edificio G',
                                'Edificio H' => 'Edificio H',
                                'Edificio I' => 'Edificio I',
                                'Edificio J' => 'Edificio J',
                                'Edificio K' => 'Edificio K',
                                'Edificio L' => 'Edificio L',
                                'Edificio M' => 'Edificio M',
                                'Edificio N' => 'Edificio N',
                                'Edificio O' => 'Edificio O',
                                'Edificio P' => 'Edificio P',
                                'Edificio Q' => 'Edificio Q',
                                'Edificio R' => 'Edificio R',
                                'Edificio S' => 'Edificio S',
                                'Edificio T' => 'Edificio T',
                                'Edificio U' => 'Edificio U',
                                'Edificio V' => 'Edificio V',
                                'Edificio W' => 'Edificio W',
                                'Edificio X' => 'Edificio X',
                                'Edificio Y' => 'Edificio Y',
                                'Edificio Z' => 'Edificio Z',
                                'Oficina Central' => 'Oficina Central',
                                'Almacén Principal' => 'Almacén Principal',
                                'Centro de Datos' => 'Centro de Datos',
                            ], ['class' => 'form-select']) ?>
                            <?= $form->field($model, 'ubicacion_detalle')->textInput([
                                'maxlength' => 255,
                                'placeholder' => 'Ej: Sala de Servidores, Piso 3, Oficina 301, etc.',
                                'class' => 'form-control'
                            ])->hint('Descripción específica de la ubicación dentro del edificio') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($model, 'DESCRIPCION')->textarea([
                                'rows' => 3,
                                'maxlength' => 100,
                                'placeholder' => 'Descripción detallada del No Break, características especiales, equipos conectados, etc.',
                                'class' => 'form-control'
                            ])->hint('Máximo 100 caracteres') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <?= Html::a('<i class="fas fa-arrow-left"></i> Volver al Menú', ['site/agregar-nuevo'], ['class' => 'btn btn-secondary me-md-2']) ?>
                        <button type="reset" class="btn btn-warning me-md-2">
                            <i class="fas fa-undo"></i> Limpiar Formulario
                        </button>
                        <?= Html::submitButton('<i class="fas fa-save"></i> Guardar No Break', [
                            'class' => 'btn btn-danger'
                        ]) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Contador de caracteres para la descripción
document.addEventListener('DOMContentLoaded', function() {
    const descripcionField = document.querySelector('#nobreak-descripcion');
    if (descripcionField) {
        descripcionField.addEventListener('input', function() {
            const maxLength = 100;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;
            const helpBlock = this.parentNode.querySelector('.help-block');
            if (helpBlock) {
                helpBlock.textContent = `${currentLength}/${maxLength} caracteres`;
                if (remaining < 20) {
                    helpBlock.classList.add('text-warning');
                }
                if (remaining < 10) {
                    helpBlock.classList.remove('text-warning');
                    helpBlock.classList.add('text-danger');
                }
                if (remaining >= 20) {
                    helpBlock.classList.remove('text-warning', 'text-danger');
                }
            }
        });
    }
});
</script>
