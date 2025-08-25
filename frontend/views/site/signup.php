<?php

/** @var yii\web\V                                   <!-- Header reorganizado correctamente -->
                    <div class="text-center mb-3">
                        <!-- Título del sistema PRIMERO -->
                        <p class="text-muted small mb-2">Sistema de inventario de altas y bajas de equipo de cómputo</p>
                        
                        <!-- Logo DESPUÉS del título del sistema -->
                        <?= Html::img('@web/../views/site/logo.jpg', [
                            'alt' => 'Logo Principal',
                            'class' => 'img-fluid rounded mb-2',
                            'style' => 'max-height: 80px; object-fit: contain;'
                        ]) ?>
                        
                        <!-- Acción específica AL FINAL -->
                        <h4 class="fw-bold text-dark mb-1">Crear Cuenta</h4>
                        <div class="border-top border-primary w-50 mx-auto mb-3" style="border-width: 2px !important;"></div>
                    </div>eader reorganizado -->
                    <div class="text-center mb-3">
                        <!-- Título del sistema primero -->
                        <h6 class="fw-bold text-primary mb-2">Sistema de inventario de altas y bajas de equipo de cómputo</h6>
                        
                        <!-- Logo después del título -->
                        <?= Html::img('@web/../views/site/logo.jpg', [
                            'alt' => 'Logo Principal',
                            'class' => 'img-fluid rounded mb-2',
                            'style' => 'max-height: 80px; object-fit: contain;'
                        ]) ?>
                        
                        <!-- Acción específica al final -->
                        <h4 class="fw-bold text-dark mb-1">Crear Cuenta</h4>
                        <div class="border-top border-primary w-50 mx-auto mb-3" style="border-width: 2px !important;"></div>
                    </div>/
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Registrarse';

// Registrar CSS para marca de agua del desarrollador
$this->registerCss("
    .dev-watermark {
        position: fixed !important;
        bottom: 25px !important;
        right: 20px !important;
        font-size: 13px !important;
        color: rgba(0, 0, 0, 0.7) !important;
        z-index: 9999 !important;
        pointer-events: none !important;
        user-select: none !important;
        font-family: 'Courier New', monospace !important;
        text-shadow: 1px 1px 3px rgba(255,255,255,0.9) !important;
        transform: rotate(-3deg) !important;
        background: linear-gradient(45deg, rgba(255,255,255,0.2), rgba(0,0,0,0.1)) !important;
        padding: 4px 8px !important;
        border-radius: 5px !important;
        opacity: 0.8 !important;
        font-weight: 600 !important;
        letter-spacing: 0.8px !important;
        border: 1px solid rgba(0,0,0,0.1) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
    }
    .dev-watermark::before { content: '💻 ' !important; }
    .dev-watermark::after { content: ' 👨‍💻' !important; }
");

// Registrar script de protección
$this->registerJsFile('@web/js/watermark-protection.js', ['position' => \yii\web\View::POS_END]);
?>
<div class="container-fluid d-flex align-items-center justify-content-center position-relative" style="min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px 0;">
    <!-- Logo de fondo -->
    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="opacity: 0.08; z-index: 1;">
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
                        <h5 class="fw-bold text-dark mb-1">Sistema de inventario de altas y bajas de equipo de cómputo</h5>
                        <h3 class="text-muted small">Crear Cuenta</h3>
                        <div class="border-top border-primary w-50 mx-auto mb-3" style="border-width: 2px !important;"></div>
                    </div>

                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= Yii::$app->session->getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash('warning')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= Yii::$app->session->getFlash('warning') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle me-2"></i><?= Yii::$app->session->getFlash('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin([
                        'id' => 'signup-form',
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

                    <?= $form->field($model, 'email')->textInput([
                        'placeholder' => 'Ingrese su email',
                        'class' => 'form-control'
                    ])->label('Email') ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Ingrese su contraseña',
                        'class' => 'form-control'
                    ])->label('Contraseña') ?>

                    <div class="d-grid gap-2 mt-3">
                        <?= Html::submitButton('<i class="fas fa-user-plus me-2"></i>Registrarse', [
                            'class' => 'btn btn-primary',
                            'style' => 'border-radius: 8px;'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="text-center mt-3">
                        <p class="text-muted small mb-2">¿Ya tienes cuenta?</p>
                        <?= Html::a('<i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión', ['site/login'], [
                            'class' => 'btn btn-outline-secondary btn-sm',
                            'style' => 'border-radius: 8px;'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Marca de agua del desarrollador -->
<div class="dev-watermark">Sistema codificado por Juan Ucan</div>
