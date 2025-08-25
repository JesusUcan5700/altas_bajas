<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Iniciar Sesión - Sistema de inventario de altas y bajas de equipo de cómputo';
$this->params['breadcrumbs'][] = 'Login';
?>
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <!-- Logo de fondo -->
    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="opacity: 0.1; z-index: 1;">
        <?= Html::img('@web/../views/site/logo1.jpg', [
            'alt' => 'Logo Fondo',
            'class' => 'img-fluid',
            'style' => 'max-width: 500px; max-height: 500px; object-fit: contain;'
        ]) ?>
    </div>
    
    <div class="row w-100 justify-content-center" style="z-index: 2; position: relative;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <!-- Logo principal arriba del formulario -->
                    <div class="text-center mb-4">
                        <?= Html::img('@web/../views/site/logo.jpg', [
                            'alt' => 'Logo Principal',
                            'class' => 'img-fluid rounded mb-3',
                            'style' => 'max-height: 120px; object-fit: contain;'
                        ]) ?>
                        <h3 class="fw-bold text-dark mb-2">Iniciar Sesión</h3>
                        <p class="text-muted">Sistema de Gestión de Equipos</p>
                        <div class="border-top border-primary w-50 mx-auto mb-3" style="border-width: 3px !important;"></div>
                    </div>

                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= Yii::$app->session->getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            'template' => '<div class="mb-3">{label}{input}{error}</div>',
                            'labelOptions' => ['class' => 'form-label fw-bold text-dark'],
                            'inputOptions' => ['class' => 'form-control form-control-lg'],
                            'errorOptions' => ['class' => 'text-danger small'],
                        ]
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Ingrese su usuario',
                        'class' => 'form-control form-control-lg'
                    ])->label('Usuario') ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Ingrese su contraseña',
                        'class' => 'form-control form-control-lg'
                    ])->label('Contraseña') ?>

                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => '<div class="form-check mb-3">{input} {label}</div>',
                        'class' => 'form-check-input',
                        'labelOptions' => ['class' => 'form-check-label text-muted'],
                    ])->label('Recordarme') ?>

                    <div class="d-grid gap-2 mt-4">
                        <?= Html::submitButton('<i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión', [
                            'class' => 'btn btn-primary btn-lg',
                            'style' => 'border-radius: 10px;'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="text-center mt-4">
                        <p class="text-muted">¿No tienes cuenta?</p>
                        <?= Html::a('<i class="fas fa-user-plus me-2"></i>Registrarse', ['site/signup'], [
                            'class' => 'btn btn-outline-secondary',
                            'style' => 'border-radius: 10px;'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
