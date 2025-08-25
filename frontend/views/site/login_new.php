<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Iniciar Sesión';
?>
<div class="container-fluid d-flex align-items-center justify-content-center position-relative" style="min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px 0;">
    <!-- Logo de fondo -->
    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="opacity: 0.08; z-index: 1;">
        <?= Html::img('@web/../views/site/logo1.jpg', [
            'alt' => 'Logo Fondo',
            'class' => 'img-fluid',
            'style' => 'max-width: 400px; max-height: 400px; object-fit: contain;'
        ]) ?>
    </div>
    
    <div class="row w-100 justify-content-center" style="z-index: 2; position: relative;">
        <div class="col-md-6 col-lg-4 col-xl-4">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <!-- Logo principal arriba del formulario -->
                    <div class="text-center mb-3">
                        <?= Html::img('@web/../views/site/logo.jpg', [
                            'alt' => 'Logo Principal',
                            'class' => 'img-fluid rounded mb-2',
                            'style' => 'max-height: 80px; object-fit: contain;'
                        ]) ?>
                        <h4 class="fw-bold text-dark mb-1">Iniciar Sesión</h4>
                        <p class="text-muted small">Sistema de Gestión de Equipos</p>
                        <div class="border-top border-primary w-50 mx-auto mb-3" style="border-width: 2px !important;"></div>
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
                            'labelOptions' => ['class' => 'form-label fw-bold text-dark small'],
                            'inputOptions' => ['class' => 'form-control'],
                            'errorOptions' => ['class' => 'text-danger small'],
                        ]
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Ingrese su usuario',
                        'class' => 'form-control'
                    ])->label('Usuario') ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Ingrese su contraseña',
                        'class' => 'form-control'
                    ])->label('Contraseña') ?>

                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => '<div class="form-check mb-3">{input} {label}</div>',
                        'class' => 'form-check-input',
                        'labelOptions' => ['class' => 'form-check-label text-muted small'],
                    ])->label('Recordarme') ?>

                    <div class="d-grid gap-2 mt-3">
                        <?= Html::submitButton('<i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión', [
                            'class' => 'btn btn-primary',
                            'style' => 'border-radius: 8px;'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="text-center mt-3">
                        <p class="text-muted small mb-2">¿No tienes cuenta?</p>
                        <?= Html::a('<i class="fas fa-user-plus me-2"></i>Registrarse', ['site/signup'], [
                            'class' => 'btn btn-outline-secondary btn-sm',
                            'style' => 'border-radius: 8px;'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
