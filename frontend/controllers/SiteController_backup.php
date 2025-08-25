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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
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
        return $this->render('index');
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
}
