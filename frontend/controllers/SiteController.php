<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use frontend\models\Nobreak;
use frontend\models\Equipo;
use frontend\models\Impresora;
use frontend\models\VideoVigilancia;
use frontend\models\Conectividad;
use frontend\models\Telefonia;
use frontend\models\Procesador;
use frontend\models\Almacenamiento;
use frontend\models\Ram;
use frontend\models\Sonido;
use frontend\models\Monitor;
use frontend\models\Microfono;
use frontend\models\Bateria;
use frontend\models\Adaptador;
use Yii;
use Exception;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup', 'login'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // Si el usuario no está autenticado, mostrar mensaje y redirigir al login
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', 'Debe iniciar sesión para acceder al sistema.');
            return $this->redirect(['site/login']);
        }
        
        return $this->render('index');
    }

    /**
     * Logout action.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Login action.
     *
     * @return \yii\web\Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \common\models\LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Signs user up.
     *
     * @return \yii\web\Response|string
     */
    public function actionSignup()
    {
        $model = new \frontend\models\SignupForm();
        if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
            if (Yii::$app->getUser()->login($user)) {
                Yii::$app->session->setFlash('success', 'Usuario registrado exitosamente con status 10.');
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionEditar()
    {
        // Si es una petición AJAX para cargar equipos
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            $action = Yii::$app->request->post('action');
            
            if ($action === 'cargar_equipos') {
                try {
                    // Verificar conexión a la base de datos
                    $connection = Yii::$app->db;
                    
                    if (!$connection) {
                        throw new Exception('No se pudo establecer conexión con la base de datos');
                    }
                    
                    // Verificar si la tabla existe
                    $tableExists = $connection->createCommand("SHOW TABLES LIKE 'nobreak'")->queryScalar();
                    if (!$tableExists) {
                        throw new Exception('La tabla "nobreak" no existe en la base de datos');
                    }
                    
                    // Verificar las columnas de la tabla
                    $tableInfo = $connection->createCommand("SHOW COLUMNS FROM nobreak")->queryAll();
                    if (empty($tableInfo)) {
                        throw new Exception('No se pudieron obtener las columnas de la tabla "nobreak"');
                    }
                    
                    // Log de información para debugging
                    Yii::info('Cargando equipos. Columnas disponibles: ' . implode(', ', array_column($tableInfo, 'Field')), __METHOD__);
                    
                    // Consulta SQL mejorada para obtener toda la información
                    $sql = "
                        SELECT 
                            idNOBREAK,
                            MARCA,
                            MODELO,
                            CAPACIDAD,
                            NUMERO_SERIE,
                            NUMERO_INVENTARIO,
                            DESCRIPCION,
                            Estado,
                            fecha,
                            ubicacion_edificio,
                            ubicacion_detalle,
                            -- Crear ubicación completa concatenando campos
                            CONCAT(
                                COALESCE(ubicacion_edificio, ''),
                                CASE 
                                    WHEN ubicacion_edificio IS NOT NULL AND ubicacion_detalle IS NOT NULL 
                                    THEN ' - ' 
                                    ELSE '' 
                                END,
                                COALESCE(ubicacion_detalle, '')
                            ) as ubicacion_completa,
                            -- Obtener fecha de creación/modificación si existe
                            COALESCE(fecha, CURDATE()) as fecha_registro
                        FROM nobreak 
                        ORDER BY idNOBREAK ASC
                    ";
                    
                    $command = $connection->createCommand($sql);
                    $equipos = $command->queryAll();
                    
                    // Si no hay equipos, crear datos de ejemplo más completos
                    if (empty($equipos)) {
                        return [
                            [
                                'id' => 1,
                                'marca' => 'APC',
                                'modelo' => 'BR1500G',
                                'numero_serie' => 'TEST123456',
                                'estado' => 'Activo',
                                'ubicacion' => 'Edificio A - Sala de Servidores',
                                'data' => [
                                    'idNOBREAK' => 1,
                                    'MARCA' => 'APC',
                                    'MODELO' => 'BR1500G',
                                    'CAPACIDAD' => '1500VA/900W',
                                    'NUMERO_SERIE' => 'TEST123456',
                                    'NUMERO_INVENTARIO' => 'INV-2024-001',
                                    'DESCRIPCION' => 'No Break APC de 1500VA para servidores críticos. Incluye software de monitoreo PowerChute.',
                                    'Estado' => 'Activo',
                                    'fecha' => '2024-01-15',
                                    'ubicacion_edificio' => 'Edificio A',
                                    'ubicacion_detalle' => 'Sala de Servidores'
                                ]
                            ]
                        ];
                    }
                    
                    // Procesar datos para la respuesta
                    $resultado = [];
                    foreach ($equipos as $equipo) {
                        $ubicacionCompleta = trim(($equipo['ubicacion_edificio'] ?? '') . 
                                                  (($equipo['ubicacion_edificio'] ?? '') && ($equipo['ubicacion_detalle'] ?? '') ? ' - ' : '') . 
                                                  ($equipo['ubicacion_detalle'] ?? ''));
                        
                        $resultado[] = [
                            'id' => $equipo['idNOBREAK'],
                            'marca' => $equipo['MARCA'] ?? 'Sin especificar',
                            'modelo' => $equipo['MODELO'] ?? 'Sin especificar',
                            'numero_serie' => $equipo['NUMERO_SERIE'] ?? 'Sin especificar',
                            'estado' => $equipo['Estado'] ?? 'Sin especificar',
                            'ubicacion' => $ubicacionCompleta ?: 'Sin especificar',
                            'data' => $equipo
                        ];
                    }
                    
                    return $resultado;
                    
                } catch (Exception $e) {
                    // Log del error para debugging
                    Yii::error('Error al cargar equipos: ' . $e->getMessage(), __METHOD__);
                    Yii::error('Stack trace: ' . $e->getTraceAsString(), __METHOD__);
                    
                    return [
                        'error' => true,
                        'message' => 'Error de base de datos: ' . $e->getMessage(),
                        'details' => [
                            'codigo_error' => $e->getCode(),
                            'archivo' => basename($e->getFile()),
                            'linea' => $e->getLine(),
                            'tipo_error' => get_class($e),
                            'timestamp' => date('Y-m-d H:i:s')
                        ],
                        'sugerencia' => 'Verifica la conexión a la base de datos y que la tabla "nobreak" exista.'
                    ];
                }
            }

            return [
                'error' => true,
                'message' => 'Acción no reconocida: ' . $action,
                'received_action' => $action,
                'post_data' => Yii::$app->request->post()
            ];
        }
        
        return $this->render('editar');
    }

    public function actionTestSimple()
    {
        return $this->render('test-simple');
    }

    public function actionSimple()
    {
        // Si es una petición AJAX, devolver datos JSON
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            try {
                // Conexión directa a la base de datos
                $connection = Yii::$app->db;
                
                // Consulta simple
                $sql = "SELECT * FROM nobreak ORDER BY idNOBREAK ASC";
                $equipos = $connection->createCommand($sql)->queryAll();
                
                // Formatear datos para el frontend
                $resultado = [];
                foreach ($equipos as $equipo) {
                    $resultado[] = [
                        'id' => $equipo['idNOBREAK'],
                        'marca' => $equipo['MARCA'],
                        'modelo' => $equipo['MODELO'],
                        'capacidad' => $equipo['CAPACIDAD'],
                        'numero_serie' => $equipo['NUMERO_SERIE'],
                        'estado' => $equipo['Estado'],
                        'ubicacion_edificio' => $equipo['ubicacion_edificio'],
                        'ubicacion_detalle' => $equipo['ubicacion_detalle'],
                        'fecha' => $equipo['fecha'],
                        'inventario' => $equipo['NUMERO_INVENTARIO'],
                        'descripcion' => $equipo['DESCRIPCION']
                    ];
                }
                
                return $resultado;
                
            } catch (Exception $e) {
                return [
                    'error' => true,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        return $this->render('simple');
    }
    
    public function actionDirecto()
    {
        return $this->render('directo');
    }
    
    public function actionAgregarNuevo()
    {
        return $this->render('agregar_nuevo');
    }
    
    public function actionGestionCategorias()
    {
        return $this->render('gestion_categorias');
    }
    
    public function actionVerEquipos()
    {
        return $this->render('ver-equipos');
    }
    
    // ==================== ACCIONES PARA NO BREAK ====================
    public function actionNobreakAgregar()
    {
        $model = new Nobreak();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'No Break agregado exitosamente.');
                return $this->redirect(['nobreak-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al agregar el No Break.');
            }
        }

        return $this->render('nobreak/agregar', ['model' => $model]);
    }
    
    public function actionNobreakListar()
    {
        try {
            $nobreaks = Nobreak::find()->orderBy('idNOBREAK ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $nobreaks = [];
            $error = $e->getMessage();
        }

        return $this->render('nobreak/listar', [
            'nobreaks' => $nobreaks,
            'error' => $error
        ]);
    }
    
    public function actionNobreakEditar($id = null)
    {
        if ($id === null) {
            Yii::$app->session->setFlash('error', 'ID de No Break no especificado.');
            return $this->redirect(['site/nobreak-listar']);
        }

        $model = Nobreak::findOne($id);
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'No Break no encontrado.');
            return $this->redirect(['site/nobreak-listar']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'No Break actualizado exitosamente.');
            return $this->redirect(['site/nobreak-listar']);
        }

        return $this->render('nobreak/editar', [
            'model' => $model,
        ]);
    }

    /**
     * Acción para cambiar estado masivo de equipos
     */
    public function actionCambiarEstadoMasivo()
    {
        if (!Yii::$app->request->isPost) {
            Yii::$app->session->setFlash('error', 'Método no permitido.');
            return $this->redirect(['site/index']);
        }

        $modelo = Yii::$app->request->post('modelo');
        $equipos = Yii::$app->request->post('equipos', []);
        $nuevoEstado = Yii::$app->request->post('nuevo_estado'); // Corregido para coincidir con el formulario

        if (empty($equipos)) {
            Yii::$app->session->setFlash('error', 'No se seleccionaron equipos.');
            return $this->redirect(['site/' . strtolower($modelo) . '-listar']);
        }

        if (empty($nuevoEstado)) {
            Yii::$app->session->setFlash('error', 'Estado no especificado.');
            return $this->redirect(['site/' . strtolower($modelo) . '-listar']);
        }

        try {
            $modelClass = "frontend\\models\\$modelo";
            if (!class_exists($modelClass)) {
                throw new \Exception("Modelo $modelo no encontrado.");
            }

            $actualizados = 0;
            $errores = [];
            
            // For Monitor model, use direct SQL update to avoid property issues
            // Use direct SQL for models with validation issues
            if ($modelo === 'Monitor' || $modelo === 'Telefonia' || $modelo === 'Videovigilancia') {
                try {
                    $equiposStr = implode(',', array_map('intval', $equipos));
                    
                    // Determine table name and primary key field
                    $tableName = '';
                    $primaryKey = '';
                    
                    if ($modelo === 'Monitor') {
                        $tableName = 'monitor';
                        $primaryKey = 'idMonitor';
                    } elseif ($modelo === 'Telefonia') {
                        $tableName = 'telefonia';
                        $primaryKey = 'idTELEFONIA';
                    } elseif ($modelo === 'Videovigilancia') {
                        $tableName = 'video_vigilancia';
                        $primaryKey = 'idVIDEO_VIGILANCIA';
                    }
                    
                    $command = Yii::$app->db->createCommand(
                        "UPDATE $tableName SET ESTADO = :estado WHERE $primaryKey IN ($equiposStr)"
                    );
                    $command->bindValue(':estado', $nuevoEstado);
                    $actualizados = $command->execute();
                    
                    if ($actualizados > 0) {
                        $mensaje = "Se actualizaron $actualizados equipo(s) al estado '$nuevoEstado'.";
                        Yii::$app->session->setFlash('success', $mensaje);
                    } else {
                        Yii::$app->session->setFlash('warning', 'No se pudo actualizar ningún equipo.');
                    }
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', 'Error al actualizar: ' . $e->getMessage());
                }
            } else {
                // For other models, use the original ActiveRecord approach
                foreach ($equipos as $equipoId) {
                    $equipo = $modelClass::findOne($equipoId);
                    if ($equipo) {
                        // Determinar el nombre del campo de estado según el modelo y verificar que existe
                        $campoEstado = null;
                        
                        // Intentar diferentes nombres de campos según el modelo
                        if ($modelo === 'Telefonia' || $modelo === 'Videovigilancia') {
                            if ($equipo->hasAttribute('ESTADO')) {
                                $campoEstado = 'ESTADO';
                            }
                        }
                        
                        // Para otros modelos, intentar Estado primero
                        if ($campoEstado === null) {
                            if ($equipo->hasAttribute('Estado')) {
                                $campoEstado = 'Estado';
                            } elseif ($equipo->hasAttribute('estado')) {
                                $campoEstado = 'estado';
                            } elseif ($equipo->hasAttribute('ESTADO')) {
                                $campoEstado = 'ESTADO';
                            }
                        }
                        
                        if ($campoEstado === null) {
                            $errores[] = "Equipo ID $equipoId: No se encontró campo de estado válido";
                            continue;
                        }
                        
                        $equipo->$campoEstado = $nuevoEstado;
                        
                        // Agregar información del editor
                        if ($equipo->hasAttribute('fecha_ultima_edicion')) {
                            $equipo->fecha_ultima_edicion = date('Y-m-d H:i:s');
                        }
                        if ($equipo->hasAttribute('ultimo_editor')) {
                            $equipo->ultimo_editor = Yii::$app->user->identity->username ?? 'Sistema';
                        }
                        
                        if ($equipo->save()) {
                            $actualizados++;
                        } else {
                            $errores[] = "Equipo ID $equipoId: " . implode(', ', $equipo->getFirstErrors());
                        }
                    } else {
                        $errores[] = "Equipo ID $equipoId no encontrado";
                    }
                }

                if ($actualizados > 0) {
                    $mensaje = "Se actualizaron $actualizados equipo(s) al estado '$nuevoEstado'.";
                    if (!empty($errores)) {
                        $mensaje .= " Errores: " . implode('; ', $errores);
                    }
                    Yii::$app->session->setFlash('success', $mensaje);
                } else {
                    $mensaje = 'No se pudo actualizar ningún equipo.';
                    if (!empty($errores)) {
                        $mensaje .= " Errores: " . implode('; ', $errores);
                    }
                    Yii::$app->session->setFlash('warning', $mensaje);
                }
            }

        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error al actualizar equipos: ' . $e->getMessage());
        }

        // Mapeo especial para redirecciones
        $redirectMap = [
            'Adaptador' => 'adaptadores-listar',
            'Monitor' => 'monitor-listar',
            'Procesador' => 'procesador-listar',
            'Ram' => 'ram-listar',
            'Bateria' => 'baterias-listar',
            'Almacenamiento' => 'almacenamiento-listar',
            'Sonido' => 'sonido-listar',
            'Microfono' => 'microfono-listar',
            'Impresora' => 'impresora-listar',
            'Equipo' => 'equipo-listar',
            'Nobreak' => 'nobreak-listar',
            'Conectividad' => 'conectividad-listar',
            'Telefonia' => 'telefonia-listar',
            'Videovigilancia' => 'videovigilancia-listar'
        ];
        
        $redirectAction = isset($redirectMap[$modelo]) ? $redirectMap[$modelo] : strtolower($modelo) . '-listar';
        return $this->redirect(['site/' . $redirectAction]);
    }
    
    // ==================== ACCIONES PARA EQUIPOS DE CÓMPUTO ====================
    public function actionEquipoAgregar()
    {
        $model = new Equipo();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Equipo agregado exitosamente.');
                return $this->redirect(['equipo-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al agregar el equipo.');
            }
        }

        return $this->render('equipo/agregar', ['model' => $model]);
    }
    
    public function actionEquipoListar()
    {
        try {
            $equipos = Equipo::find()->orderBy('idEQUIPO ASC')->all();
            
            // Obtener información del último equipo modificado usando campos de auditoría
            $ultimaModificacion = null;
            try {
                // Buscar el equipo con la fecha de edición más reciente
                $equipoMasReciente = Equipo::find()
                    ->orderBy('fecha_ultima_edicion DESC')
                    ->one();
                
                $totalEquipos = count($equipos);
                $equiposActivos = 0;
                
                foreach ($equipos as $equipo) {
                    if ($equipo->Estado === 'activo') {
                        $equiposActivos++;
                    }
                }
                
                if ($equipoMasReciente && !empty($equipoMasReciente->fecha_ultima_edicion)) {
                    // Calcular tiempo desde la última edición
                    $fechaUltima = new \DateTime($equipoMasReciente->fecha_ultima_edicion);
                    $fechaActual = new \DateTime();
                    $diferencia = $fechaActual->diff($fechaUltima);
                    
                    $tiempoTranscurrido = '';
                    if ($diferencia->days == 0) {
                        if ($diferencia->h == 0) {
                            $tiempoTranscurrido = 'Hace ' . $diferencia->i . ' minutos';
                        } else {
                            $tiempoTranscurrido = 'Hace ' . $diferencia->h . ' horas';
                        }
                    } elseif ($diferencia->days == 1) {
                        $tiempoTranscurrido = 'Ayer';
                    } else {
                        $tiempoTranscurrido = 'Hace ' . $diferencia->days . ' días';
                    }
                    
                    // Obtener información del usuario que editó
                    $userInfo = $equipoMasReciente->getInfoUsuarioEditor();
                    
                    $ultimaModificacion = [
                        'equipo' => $equipoMasReciente->MARCA . ' ' . $equipoMasReciente->MODELO,
                        'id' => $equipoMasReciente->idEQUIPO,
                        'fecha_edicion' => $equipoMasReciente->fecha_ultima_edicion,
                        'usuario' => $userInfo['username'],
                        'usuario_email' => $userInfo['email'],
                        'usuario_display' => $userInfo['display_name'],
                        'fecha_formateada' => date('d/m/Y H:i', strtotime($equipoMasReciente->fecha_ultima_edicion)),
                        'tiempo_transcurrido' => $tiempoTranscurrido,
                        'total_equipos' => $totalEquipos,
                        'equipos_activos' => $equiposActivos
                    ];
                }
            } catch (Exception $e) {
                // Si hay error, continuar sin la información de última modificación
                $ultimaModificacion = null;
            }
            
            $error = null;
        } catch (Exception $e) {
            $equipos = [];
            $ultimaModificacion = null;
            $error = $e->getMessage();
        }

        return $this->render('equipo/listar', [
            'equipos' => $equipos,
            'ultimaModificacion' => $ultimaModificacion,
            'error' => $error
        ]);
    }
    
    public function actionEquipoEditar($id = null)
    {
        if ($id === null) {
            Yii::$app->session->setFlash('error', 'ID de Equipo no especificado.');
            return $this->redirect(['site/equipo-listar']);
        }

        $model = Equipo::findOne($id);
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'Equipo no encontrado.');
            return $this->redirect(['site/equipo-listar']);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Procesar campos DD2, DD3, DD4 si no están marcados
            if (empty($model->DD2) || $model->DD2 === '') {
                $model->DD2 = 'NO';
            }
            if (empty($model->DD3) || $model->DD3 === '') {
                $model->DD3 = 'NO';
            }
            if (empty($model->DD4) || $model->DD4 === '') {
                $model->DD4 = 'NO';
            }
            
            // Procesar campos RAM2, RAM3, RAM4 si no están marcados
            if (empty($model->RAM2) || $model->RAM2 === '') {
                $model->RAM2 = 'NO';
            }
            if (empty($model->RAM3) || $model->RAM3 === '') {
                $model->RAM3 = 'NO';
            }
            if (empty($model->RAM4) || $model->RAM4 === '') {
                $model->RAM4 = 'NO';
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Equipo actualizado exitosamente.');
                return $this->redirect(['site/equipo-listar']);
            } else {
                // Mostrar errores específicos de validación
                $errors = [];
                foreach ($model->getErrors() as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errors[] = "$field: $error";
                    }
                }
                $errorMessage = empty($errors) ? 'Error desconocido al actualizar el equipo.' : 'Errores de validación: ' . implode('; ', $errors);
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('equipo/editar', [
            'model' => $model,
        ]);
    }
    
    /**
     * Procesa campos especiales del formulario de equipo
     */
    private function procesarCamposEspeciales($model)
    {
        // Procesar campos DD que no están seleccionados
        if (empty($model->DD2) || $model->DD2 === 'NO') {
            $model->DD2 = 'NO';
        }
        if (empty($model->DD3) || $model->DD3 === 'NO') {
            $model->DD3 = 'NO';
        }
        if (empty($model->DD4) || $model->DD4 === 'NO') {
            $model->DD4 = 'NO';
        }
        
        // Procesar campos RAM que no están seleccionados
        if (empty($model->RAM2) || $model->RAM2 === 'NO') {
            $model->RAM2 = 'NO';
        }
        if (empty($model->RAM3) || $model->RAM3 === 'NO') {
            $model->RAM3 = 'NO';
        }
        if (empty($model->RAM4) || $model->RAM4 === 'NO') {
            $model->RAM4 = 'NO';
        }
    }
    
    // ==================== ACCIONES PARA IMPRESORAS ====================
    public function actionImpresoraAgregar()
    {
        $model = new Impresora();
        
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                
                // Configurar fecha de creación si no está establecida
                if (empty($model->fecha_creacion)) {
                    $model->fecha_creacion = date('Y-m-d H:i:s');
                }
                
                if ($model->save()) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'success' => true,
                            'message' => 'Impresora agregada exitosamente.',
                            'redirect' => Url::to(['site/impresora-listar'])
                        ];
                    } else {
                        Yii::$app->session->setFlash('success', 'Impresora agregada exitosamente.');
                        return $this->redirect(['site/impresora-listar']);
                    }
                } else {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'success' => false,
                            'errors' => $model->getErrors(),
                            'message' => 'No se pudo guardar la impresora.'
                        ];
                    } else {
                        Yii::$app->session->setFlash('error', 'No se pudo guardar la impresora. Revisa los errores.');
                    }
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'errors' => $model->getErrors(),
                        'message' => 'Datos inválidos.'
                    ];
                }
            }
        }
        
        return $this->render('impresora/agregar', [
            'model' => $model,
        ]);
    }
    
    public function actionImpresoraListar()
    {
        try {
            $impresoras = Impresora::find()->orderBy('idIMPRESORA ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $impresoras = [];
            $error = $e->getMessage();
        }

        return $this->render('impresora/listar', [
            'impresoras' => $impresoras,
            'error' => $error
        ]);
    }
    
    public function actionImpresoraEditar($id = null)
    {
        if ($id === null) {
            Yii::$app->session->setFlash('error', 'ID de Impresora no especificado.');
            return $this->redirect(['site/impresora-listar']);
        }

        $model = Impresora::findOne($id);
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'Impresora no encontrada.');
            return $this->redirect(['site/impresora-listar']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Impresora actualizada exitosamente.');
                return $this->redirect(['site/impresora-listar']);
            } else {
                // Mostrar errores específicos de validación
                $errors = [];
                foreach ($model->getErrors() as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errors[] = "$field: $error";
                    }
                }
                $errorMessage = empty($errors) ? 'Error desconocido al actualizar la impresora.' : 'Errores de validación: ' . implode('; ', $errors);
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('impresora/editar', [
            'model' => $model,
        ]);
    }
    
    // ==================== ACCIONES PARA MONITORES ====================
    public function actionMonitorAgregar()
    {
        $model = new Monitor();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Monitor agregado exitosamente.');
                return $this->redirect(['monitor-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al agregar el monitor.');
            }
        }

        return $this->render('monitor/agregar', ['model' => $model]);
    }
    
    public function actionMonitorListar()
    {
        try {
                        $monitores = Monitor::find()->orderBy('idMonitor ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $monitores = [];
            $error = $e->getMessage();
        }

        return $this->render('monitor/listar', [
            'monitores' => $monitores,
            'error' => $error
        ]);
    }
    
    public function actionMonitorEditar($id = null)
    {
        if ($id === null) {
            throw new \yii\web\BadRequestHttpException('ID no proporcionado.');
        }

        $model = Monitor::findOne($id);
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('Monitor no encontrado.');
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Monitor actualizado exitosamente.');
                return $this->redirect(['monitor-listar']);
            } else {
                // Mostrar errores específicos de validación
                $errors = [];
                foreach ($model->getErrors() as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errors[] = "$field: $error";
                    }
                }
                $errorMessage = empty($errors) ? 'Error desconocido al actualizar el monitor.' : 'Errores de validación: ' . implode('; ', $errors);
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('monitor/editar', ['model' => $model]);
    }
    
    // ==================== ACCIONES PARA MICRÓFONOS ====================
    public function actionMicrofonoAgregar()
    {
        return $this->render('microfono/agregar');
    }
    
    public function actionMicrofonoListar()
    {
        try {
            $microfonos = Microfono::find()->orderBy('idMicrofono ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $microfonos = [];
            $error = $e->getMessage();
        }

        return $this->render('microfono/listar', [
            'microfonos' => $microfonos,
            'error' => $error
        ]);
    }
    
    public function actionMicrofonoEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de micrófono no proporcionado.');
            return $this->redirect(['microfono-listar']);
        }

        $model = Microfono::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Micrófono no encontrado.');
            return $this->redirect(['microfono-listar']);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Micrófono actualizado exitosamente.');
                return $this->redirect(['microfono-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar el micrófono.');
            }
        }

        return $this->render('microfono/editar', ['model' => $model]);
    }
    
    // ==================== ACCIONES ADICIONALES ====================
    public function actionReportes()
    {
        return $this->render('reportes');
    }
    
    // ==================== ACCIONES PARA ADAPTADORES ====================
    public function actionAdaptadores()
    {
        $model = new Adaptador();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Adaptador agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('adaptadores', [
            'model' => $model,
        ]);
    }
    
    public function actionAdaptadorAgregar()
    {
        $model = new Adaptador();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Adaptador agregado exitosamente.');
                return $this->redirect(['adaptadores-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al agregar el adaptador.');
            }
        }

        return $this->render('adaptador/agregar', ['model' => $model]);
    }
    
    public function actionAdaptadoresListar()
    {
        try {
            $adaptadores = Adaptador::find()->orderBy('idAdaptador ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $adaptadores = [];
            $error = $e->getMessage();
        }

        return $this->render('adaptador/listar', [
            'adaptadores' => $adaptadores,
            'error' => $error
        ]);
    }
    
    public function actionAdaptadorEditar($id = null)
    {
        if ($id === null) {
            Yii::$app->session->setFlash('error', 'ID de Adaptador no especificado.');
            return $this->redirect(['site/adaptadores-listar']);
        }

        $model = Adaptador::findOne($id);
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'Adaptador no encontrado.');
            return $this->redirect(['site/adaptadores-listar']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Adaptador actualizado exitosamente.');
                return $this->redirect(['site/adaptadores-listar']);
            } else {
                // Mostrar errores específicos de validación
                $errors = [];
                foreach ($model->getErrors() as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errors[] = "$field: $error";
                    }
                }
                $errorMessage = empty($errors) ? 'Error desconocido al actualizar el adaptador.' : 'Errores de validación: ' . implode('; ', $errors);
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('adaptador/editar', [
            'model' => $model,
        ]);
    }
    
    // ==================== ACCIONES PARA BATERÍAS ====================
    public function actionBaterias()
    {
        $model = new Bateria();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Batería agregada exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('baterias', [
            'model' => $model,
        ]);
    }
    
    public function actionBateriasListar()
    {
        try {
            $baterias = Bateria::find()->orderBy('idBateria ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $baterias = [];
            $error = $e->getMessage();
        }

        return $this->render('bateria/listar', [
            'baterias' => $baterias,
            'error' => $error
        ]);
    }
    
    public function actionBateriaEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de batería no proporcionado.');
            return $this->redirect(['baterias-listar']);
        }

        $model = Bateria::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Batería no encontrada.');
            return $this->redirect(['baterias-listar']);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Batería actualizada exitosamente.');
                return $this->redirect(['baterias-listar']);
            } else {
                // Mostrar errores específicos de validación
                $errors = [];
                foreach ($model->getErrors() as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errors[] = "$field: $error";
                    }
                }
                $errorMessage = empty($errors) ? 'Error desconocido al actualizar la batería.' : 'Errores de validación: ' . implode('; ', $errors);
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('bateria/editar', ['model' => $model]);
    }
    
    // ==================== ACCIONES PARA ALMACENAMIENTO ====================
    public function actionDispositivosDeAlmacenamiento()
    {
        $model = new Almacenamiento();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Dispositivo de almacenamiento agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('dispositivos-de-almacenamiento', [
            'model' => $model,
        ]);
    }
    
    public function actionAlmacenamientoAgregar()
    {
        $model = new Almacenamiento();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Dispositivo de almacenamiento agregado exitosamente.');
                return $this->redirect(['almacenamiento-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al agregar el dispositivo de almacenamiento.');
            }
        }

        return $this->render('almacenamiento/agregar', ['model' => $model]);
    }
    
    public function actionAlmacenamientoListar()
    {
        try {
            $almacenamientos = Almacenamiento::find()->orderBy('idAlmacenamiento ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $almacenamientos = [];
            $error = 'Error al cargar dispositivos de almacenamiento: ' . $e->getMessage();
        }
        
        return $this->render('almacenamiento/listar', [
            'almacenamientos' => $almacenamientos,
            'error' => $error
        ]);
    }
    
    public function actionAlmacenamientoEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de dispositivo de almacenamiento no proporcionado.');
            return $this->redirect(['almacenamiento-listar']);
        }

        $model = Almacenamiento::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Dispositivo de almacenamiento no encontrado.');
            return $this->redirect(['almacenamiento-listar']);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Dispositivo de almacenamiento actualizado exitosamente.');
                return $this->redirect(['almacenamiento-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar el dispositivo de almacenamiento.');
            }
        }

        return $this->render('almacenamiento/editar', ['model' => $model]);
    }
    
    // ==================== ACCIONES PARA MEMORIA RAM ====================
    public function actionMemoriaRam()
    {
        $model = new Ram();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Memoria RAM agregada exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('memoria-ram', [
            'model' => $model,
        ]);
    }
    
    public function actionRamListar()
    {
        try {
            $rams = Ram::find()->orderBy('idRAM ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $rams = [];
            $error = $e->getMessage();
        }

        return $this->render('ram/listar', [
            'rams' => $rams,
            'error' => $error
        ]);
    }
    
    public function actionRamEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de memoria RAM no proporcionado.');
            return $this->redirect(['ram-listar']);
        }

        $model = Ram::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Memoria RAM no encontrada.');
            return $this->redirect(['ram-listar']);
        }

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            
            // Remover campos de auditoría del POST para evitar conflictos
            if (isset($postData['Ram']['fecha_creacion'])) {
                unset($postData['Ram']['fecha_creacion']);
            }
            if (isset($postData['Ram']['fecha_ultima_edicion'])) {
                unset($postData['Ram']['fecha_ultima_edicion']);
            }
            
            if ($model->load($postData) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Memoria RAM actualizada exitosamente.');
                return $this->redirect(['ram-listar']);
            } else {
                // Mostrar errores específicos de validación
                $errors = [];
                foreach ($model->getErrors() as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errors[] = "$field: $error";
                    }
                }
                $errorMessage = empty($errors) ? 'Error desconocido al actualizar la memoria RAM.' : 'Errores de validación: ' . implode('; ', $errors);
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('ram/editar', ['model' => $model]);
    }
    
    // ==================== ACCIONES PARA EQUIPO DE SONIDO ====================
    public function actionEquipoDeSonido()
    {
        $model = new Sonido();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Equipo de sonido agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('equipo-de-sonido', [
            'model' => $model,
        ]);
    }
    
    public function actionSonidoListar()
    {
        try {
            $sonidos = Sonido::find()->orderBy('idSonido ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $sonidos = [];
            $error = $e->getMessage();
        }

        return $this->render('sonido/listar', [
            'sonidos' => $sonidos,
            'error' => $error
        ]);
    }
    
    public function actionSonidoEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de equipo de sonido no proporcionado.');
            return $this->redirect(['sonido-listar']);
        }

        $model = Sonido::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Equipo de sonido no encontrado.');
            return $this->redirect(['sonido-listar']);
        }

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            
            // Remover campos de auditoría del POST para evitar conflictos
            if (isset($postData['Sonido']['fecha_creacion'])) {
                unset($postData['Sonido']['fecha_creacion']);
            }
            if (isset($postData['Sonido']['fecha_ultima_edicion'])) {
                unset($postData['Sonido']['fecha_ultima_edicion']);
            }
            
            if ($model->load($postData) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Equipo de sonido actualizado exitosamente.');
                return $this->redirect(['sonido-listar']);
            } else {
                // Mostrar errores específicos de validación
                $errors = [];
                foreach ($model->getErrors() as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errors[] = "$field: $error";
                    }
                }
                $errorMessage = empty($errors) ? 'Error desconocido al actualizar el equipo de sonido.' : 'Errores de validación: ' . implode('; ', $errors);
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('sonido/editar', ['model' => $model]);
    }
    
    // ==================== ACCIONES PARA PROCESADORES ====================
    public function actionProcesadores()
    {
        $model = new Procesador();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Procesador agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('procesadores', [
            'model' => $model,
        ]);
    }
    
    public function actionProcesadorListar()
    {
        try {
            $procesadores = Procesador::find()->orderBy('idProcesador ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $procesadores = [];
            $error = $e->getMessage();
        }

        return $this->render('procesador/listar', [
            'procesadores' => $procesadores,
            'error' => $error
        ]);
    }
    
    public function actionProcesadorEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de procesador requerido.');
            return $this->redirect(['procesador-listar']);
        }

        $model = Procesador::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Procesador no encontrado.');
            return $this->redirect(['procesador-listar']);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Filtrar solo los campos que queremos actualizar
            $allowedFields = [
                'MARCA', 'MODELO', 'FRECUENCIA_BASE', 'NUCLEOS', 'HILOS',
                'NUMERO_SERIE', 'NUMERO_INVENTARIO', 'DESCRIPCION', 'Estado',
                'fecha', 'ubicacion_edificio', 'ubicacion_detalle'
            ];
            
            $postData = Yii::$app->request->post('Procesador', []);
            foreach ($postData as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $model->$field = $value;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Procesador actualizado exitosamente.');
                return $this->redirect(['procesador-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar el procesador: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        return $this->render('procesador/editar', [
            'model' => $model,
        ]);
    }
    
    // ==================== ACCIONES PARA CONECTIVIDAD ====================
    public function actionConectividad()
    {
        $model = new Conectividad();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Equipo de conectividad agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('conectividad', [
            'model' => $model,
        ]);
    }
    
    public function actionConectividadListar()
    {
        try {
            $conectividades = Conectividad::find()->orderBy('idCONECTIVIDAD ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $conectividades = [];
            $error = $e->getMessage();
        }

        return $this->render('conectividad/listar', [
            'conectividades' => $conectividades,
            'error' => $error
        ]);
    }
    
    public function actionConectividadEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de conectividad requerido.');
            return $this->redirect(['conectividad-listar']);
        }

        $model = Conectividad::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Equipo de conectividad no encontrado.');
            return $this->redirect(['conectividad-listar']);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Filtrar solo los campos que queremos actualizar
            $allowedFields = [
                'TIPO', 'MARCA', 'MODELO', 'NUMERO_SERIE', 'NUMERO_INVENTARIO',
                'CANTIDAD_PUERTOS', 'DESCRIPCION', 'Estado', 'fecha',
                'ubicacion_edificio', 'ubicacion_detalle'
            ];
            
            $postData = Yii::$app->request->post('Conectividad', []);
            foreach ($postData as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $model->$field = $value;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Equipo de conectividad actualizado exitosamente.');
                return $this->redirect(['conectividad-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar el equipo: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        return $this->render('conectividad/editar', [
            'model' => $model,
        ]);
    }
    
    // ==================== ACCIONES PARA TELEFONÍA ====================
    public function actionTelefonia()
    {
        $model = new Telefonia();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Equipo de telefonía agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('telefonia', [
            'model' => $model,
        ]);
    }
    
    public function actionTelefoniaListar()
    {
        try {
            $telefonias = Telefonia::find()->orderBy('idTELEFONIA ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $telefonias = [];
            $error = $e->getMessage();
        }

        return $this->render('telefonia/listar', [
            'telefonias' => $telefonias,
            'error' => $error
        ]);
    }
    
    public function actionTelefoniaEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de telefonía requerido.');
            return $this->redirect(['telefonia-listar']);
        }

        $model = Telefonia::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Equipo de telefonía no encontrado.');
            return $this->redirect(['telefonia-listar']);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Filtrar solo los campos que queremos actualizar
            $allowedFields = [
                'MARCA', 'MODELO', 'NUMERO_SERIE', 'NUMERO_INVENTARIO',
                'EDIFICIO', 'ESTADO', 'EMISION_INVENTARIO', 'fecha',
                'ubicacion_edificio', 'ubicacion_detalle'
            ];
            
            $postData = Yii::$app->request->post('Telefonia', []);
            foreach ($postData as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $model->$field = $value;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Equipo de telefonía actualizado exitosamente.');
                return $this->redirect(['telefonia-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar el equipo: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        return $this->render('telefonia/editar', [
            'model' => $model,
        ]);
    }
    
    // ==================== ACCIONES PARA CÁMARAS/VIDEO VIGILANCIA ====================
    public function actionCamaras()
    {
        $model = new VideoVigilancia();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Cámara agregada exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('camaras', [
            'model' => $model,
        ]);
    }
    
    public function actionVideovigilanciaListar()
    {
        try {
            $camaras = VideoVigilancia::find()->orderBy('idVIDEO_VIGILANCIA ASC')->all();
            $error = null;
        } catch (Exception $e) {
            $camaras = [];
            $error = $e->getMessage();
        }

        return $this->render('videovigilancia/listar', [
            'camaras' => $camaras,
            'error' => $error
        ]);
    }
    
    public function actionVideovigilanciaEditar($id = null)
    {
        if (!$id) {
            Yii::$app->session->setFlash('error', 'ID de cámara requerido.');
            return $this->redirect(['videovigilancia-listar']);
        }

        $model = VideoVigilancia::findOne($id);
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Cámara de videovigilancia no encontrada.');
            return $this->redirect(['videovigilancia-listar']);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Filtrar solo los campos que queremos actualizar
            $allowedFields = [
                'MARCA', 'MODELO', 'NUMERO_SERIE', 'NUMERO_INVENTARIO', 'DESCRIPCION',
                'tipo_camara', 'EDIFICIO', 'ESTADO', 'fecha', 'ubicacion_edificio', 
                'ubicacion_detalle', 'EMISION_INVENTARIO', 'VIDEO_VIGILANCIA_COL'
            ];
            
            $postData = Yii::$app->request->post('VideoVigilancia', []);
            foreach ($postData as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $model->$field = $value;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Cámara de videovigilancia actualizada exitosamente.');
                return $this->redirect(['videovigilancia-listar']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar la cámara: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        return $this->render('videovigilancia/editar', [
            'model' => $model,
        ]);
    }
    
    // ==================== ACCIONES PARA COMPATIBILIDAD CON AGREGAR_NUEVO.PHP ====================
    public function actionMonitores()
    {
        $model = new Monitor();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Monitor agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('monitores', [
            'model' => $model,
        ]);
    }
    
    public function actionMicrofonos()
    {
        $model = new Microfono();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Micrófono agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('microfonos', [
            'model' => $model,
        ]);
    }
    
    public function actionNoBreak()
    {
        $model = new Nobreak();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'No Break agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('no-break', [
            'model' => $model,
        ]);
    }
    
    public function actionComputo()
    {
        $model = new Equipo();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Equipo de cómputo agregado exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('computo', [
            'model' => $model,
        ]);
    }
    
    public function actionImpresora()
    {
        $model = new Impresora();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Impresora agregada exitosamente.');
            return $this->refresh();
        }
        
        return $this->render('impresora', [
            'model' => $model,
        ]);
    }
    
    public function actionStock()
    {
        return $this->render('stock');
    }

    /**
     * Devuelve el listado de dispositivos inactivos para una categoría dada.
     * Endpoint: /site/stock-inactivos?categoria=<key>
     * Retorna JSON: { categoria, nombre, items: [...] }
     */
    public function actionStockInactivos($categoria = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($categoria === null) {
            return [
                'error' => true,
                'message' => 'Parámetro "categoria" es requerido'
            ];
        }

        // Mapeo de categorías (debe coincidir con la vista stock)
        $categorias = [
            'nobreak' => ['tabla' => 'nobreak', 'nombre' => 'No Break / UPS', 'id_field' => 'idNOBREAK'],
            'equipo' => ['tabla' => 'equipo', 'nombre' => 'Equipos de Cómputo', 'id_field' => 'idEQUIPO'],
            'impresora' => ['tabla' => 'impresora', 'nombre' => 'Impresoras', 'id_field' => 'idIMPRESORA'],
            'monitor' => ['tabla' => 'monitor', 'nombre' => 'Monitores', 'id_field' => 'idMonitor'],
            'baterias' => ['tabla' => 'baterias', 'nombre' => 'Baterías', 'id_field' => 'id'],
            'almacenamiento' => ['tabla' => 'almacenamiento', 'nombre' => 'Almacenamiento', 'id_field' => 'idAlmacenamiento'],
            'memoria_ram' => ['tabla' => 'memoria_ram', 'nombre' => 'Memoria RAM', 'id_field' => 'id'],
            'equipo_sonido' => ['tabla' => 'equipo_sonido', 'nombre' => 'Equipo de Sonido', 'id_field' => 'id'],
            'procesadores' => ['tabla' => 'procesadores', 'nombre' => 'Procesadores', 'id_field' => 'id'],
            'conectividad' => ['tabla' => 'conectividad', 'nombre' => 'Conectividad', 'id_field' => 'idCONECTIVIDAD'],
            'telefonia' => ['tabla' => 'telefonia', 'nombre' => 'Telefonía', 'id_field' => 'idTELEFONIA'],
            'video_vigilancia' => ['tabla' => 'video_vigilancia', 'nombre' => 'Video Vigilancia', 'id_field' => 'id'],
            'adaptadores' => ['tabla' => 'adaptadores', 'nombre' => 'Adaptadores', 'id_field' => 'id']
        ];

        if (!isset($categorias[$categoria])) {
            return [
                'error' => true,
                'message' => 'Categoría no reconocida: ' . $categoria
            ];
        }

        $desc = $categorias[$categoria];
        $tabla = $desc['tabla'];

        try {
            $connection = Yii::$app->db;

            // Verificar existencia de la tabla
            $tablaExiste = $connection->createCommand("SHOW TABLES LIKE :tabla")->bindValue(':tabla', $tabla)->queryOne();
            if (!$tablaExiste) {
                return [
                    'error' => true,
                    'message' => "Tabla '$tabla' no existe en la base de datos"
                ];
            }

            // Determinar columna de estado disponible
            $cols = $connection->createCommand("SHOW COLUMNS FROM $tabla")->queryAll();
            $colNames = array_column($cols, 'Field');
            $candidatoEstado = null;
            foreach (['Estado', 'ESTADO', 'estado'] as $c) {
                if (in_array($c, $colNames)) { $candidatoEstado = $c; break; }
            }

            if ($candidatoEstado === null) {
                return [
                    'error' => true,
                    'message' => "No se encontró columna de estado en la tabla '$tabla'"
                ];
            }

            // Obtener filas que no estén activas (case-insensitive)
            $sql = "SELECT * FROM $tabla WHERE LOWER(COALESCE($candidatoEstado, '')) != 'activo' ORDER BY 1 LIMIT 1000";
            $rows = $connection->createCommand($sql)->queryAll();

            $items = [];
            foreach ($rows as $r) {
                $items[] = [
                    'id' => isset($r[$desc['id_field']]) ? $r[$desc['id_field']] : (isset($r['id']) ? $r['id'] : null),
                    'MARCA' => $r['MARCA'] ?? ($r['marca'] ?? null),
                    'MODELO' => $r['MODELO'] ?? ($r['modelo'] ?? null),
                    'NUMERO_SERIE' => $r['NUMERO_SERIE'] ?? ($r['numero_serie'] ?? null),
                    'NUMERO_INVENTARIO' => $r['NUMERO_INVENTARIO'] ?? ($r['numero_inventario'] ?? null),
                    'Estado' => $r[$candidatoEstado] ?? null,
                    'ubicacion_edificio' => $r['ubicacion_edificio'] ?? ($r['ubicacion'] ?? null),
                    'ubicacion_detalle' => $r['ubicacion_detalle'] ?? null,
                    'data' => $r
                ];
            }

            return [
                'error' => false,
                'categoria' => $categoria,
                'nombre' => $desc['nombre'],
                'count' => count($items),
                'items' => $items
            ];

        } catch (Exception $e) {
            Yii::error('Error actionStockInactivos: ' . $e->getMessage(), __METHOD__);
            return [
                'error' => true,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ];
        }
    }
}
