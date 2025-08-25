<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Microfono */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Editar Micrófono';

// Agregar JavaScript para contador de caracteres
$this->registerJs("
    $('#microfono-descripcion').on('input', function() {
        var remaining = 100 - $(this).val().length;
        $('#char-count').text(remaining + ' caracteres restantes');
        if (remaining < 10) {
            $('#char-count').removeClass('text-muted').addClass('text-danger');
        } else {
            $('#char-count').removeClass('text-danger').addClass('text-muted');
        }
    });
    // Trigger on page load
    $('#microfono-descripcion').trigger('input');
");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-microphone me-2"></i><?= Html::encode($this->title) ?>
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
                                <?= $form->field($model, 'MARCA')->dropDownList(frontend\models\Microfono::getMarcas(), ['prompt' => 'Selecciona Marca']) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'MODELO')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'TIPO')->dropDownList(frontend\models\Microfono::getTipos(), ['prompt' => 'Selecciona Tipo']) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'PATRON_POLAR')->dropDownList(frontend\models\Microfono::getPatronesPolar(), ['prompt' => 'Selecciona Patrón Polar']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'CONECTIVIDAD')->dropDownList(frontend\models\Microfono::getConectividad(), ['prompt' => 'Selecciona Conectividad']) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'FRECUENCIA_RESPUESTA')->textInput(['maxlength' => true, 'placeholder' => 'Ej: 20Hz-20kHz']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'NUMERO_SERIE')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'NUMERO_INVENTARIO')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'ESTADO')->dropDownList(frontend\models\Microfono::getEstados(), ['prompt' => 'Selecciona Estado']) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'ubicacion_edificio')->dropDownList(frontend\models\Microfono::getEdificios(), ['prompt' => 'Selecciona Edificio']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'ubicacion_detalle')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'FECHA')->input('date') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <?= $form->field($model, 'DESCRIPCION')->textarea(['rows' => 3, 'maxlength' => 100]) ?>
                                <small id="char-count" class="text-muted">100 caracteres restantes</small>
                            </div>
                        </div>
                        <div class="form-group text-center mt-4">
                            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success btn-lg me-2']) ?>
                            <?= Html::a('<i class="fas fa-list me-2"></i>Listar Microfonos', ['site/microfono-listar'], ['class' => 'btn btn-info btn-lg me-2']) ?>
                            <?= Html::a('<i class="fas fa-arrow-left me-2"></i>Volver a Agregar Nuevo', ['site/microfono-agregar'], ['class' => 'btn btn-secondary btn-lg me-2']) ?>
                            <?= Html::a('<i class="fas fa-home me-2"></i>Menú Principal', ['site/index'], ['class' => 'btn btn-outline-secondary btn-lg']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
