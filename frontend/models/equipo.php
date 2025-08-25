<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "equipo".
 *
 * @property int $idEQUIPO
 * @property string $CPU
 * @property string $DD
 * @property string $DD2
 * @property string $DD3
 * @property string $DD4
 * @property string $RAM
 * @property string $RAM2
 * @property string $RAM3
 * @property string $RAM4
 * @property string $MARCA
 * @property string $MODELO
 * @property string $NUM_SERIE
 * @property string $NUM_INVENTARIO
 * @property string $EMISION_INVENTARIO
 * @property string $Estado
 * @property string $tipoequipo
 * @property string $ubicacion_edificio
 * @property string $ubicacion_detalle
 * @property string $descripcion
 * @property string $fecha_creacion
 * @property string $fecha_ultima_edicion
 * @property string $ultimo_editor
 */
class Equipo extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVO = 'Activo';
    const ESTADO_INACTIVO = 'Inactivo(Sin Asignar)';
    const ESTADO_DANADO = 'dañado(Proceso de baja)';
    const ESTADO_MANTENIMIENTO = 'En Mantenimiento';
    const ESTADO_BAJA = 'BAJA';

    const TIPO_PC = 'PC';
    const TIPO_LAPTOP = 'Laptop';
    const TIPO_SERVIDOR = 'Servidor';
    const TIPO_OTRO = 'Otro';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipo';
    }

    /**
     * {@inheritdoc}
     */
    public static function primaryKey()
    {
        return ['idEQUIPO'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CPU', 'DD', 'RAM', 'MARCA', 'MODELO', 'NUM_SERIE', 'NUM_INVENTARIO', 'EMISION_INVENTARIO', 'tipoequipo'], 'required'],
            [['CPU', 'DD', 'DD2', 'DD3', 'DD4', 'RAM', 'RAM2', 'RAM3', 'RAM4', 'MARCA', 'MODELO'], 'string', 'max' => 45],
            [['NUM_SERIE', 'NUM_INVENTARIO', 'EMISION_INVENTARIO'], 'string', 'max' => 45],
            [['tipoequipo'], 'string', 'max' => 100],
            [['ubicacion_edificio'], 'string', 'max' => 15],
            [['ubicacion_detalle', 'descripcion'], 'string', 'max' => 255],
            [['ultimo_editor'], 'string', 'max' => 100],
            [['fecha_creacion', 'fecha_ultima_edicion'], 'safe'],
            [['EMISION_INVENTARIO'], 'date', 'format' => 'yyyy-MM-dd'],
            [['NUM_SERIE'], 'unique'],
            [['NUM_INVENTARIO'], 'unique'],
            [['Estado'], 'string'],
            [['Estado'], 'in', 'range' => array_keys(self::getEstados())],
            [['Estado'], 'default', 'value' => self::ESTADO_ACTIVO],
            [['tipoequipo'], 'default', 'value' => self::TIPO_PC],
            [['EMISION_INVENTARIO'], 'default', 'value' => date('Y-m-d')],
            [['ultimo_editor'], 'default', 'value' => 'Sistema'],
            [['ubicacion_edificio'], 'in', 'range' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idEQUIPO' => 'ID Equipo',
            'CPU' => 'CPU',
            'DD' => 'Disco Duro',
            'DD2' => 'Disco Duro 2',
            'DD3' => 'Disco Duro 3',
            'DD4' => 'Disco Duro 4',
            'RAM' => 'RAM',
            'RAM2' => 'RAM 2',
            'RAM3' => 'RAM 3',
            'RAM4' => 'RAM 4',
            'MARCA' => 'Marca',
            'MODELO' => 'Modelo',
            'NUM_SERIE' => 'Número de Serie',
            'NUM_INVENTARIO' => 'Número de Inventario',
            'EMISION_INVENTARIO' => 'Emisión de Inventario',
            'Estado' => 'Estado',
            'tipoequipo' => 'Tipo de Equipo',
            'ubicacion_edificio' => 'Edificio',
            'ubicacion_detalle' => 'Ubicación Detallada',
            'descripcion' => 'Descripción',
            'fecha_creacion' => 'Fecha de Creación',
            'fecha_ultima_edicion' => 'Última Edición',
            'ultimo_editor' => 'Último Editor',
        ];
    }

    /**
     * Comportamientos del modelo
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'fecha_creacion',
                'updatedAtAttribute' => 'fecha_ultima_edicion',
                'value' => new Expression('NOW()'),
                'skipUpdateOnClean' => false,
            ],
        ];
    }

    /**
     * Obtiene los estados disponibles
     */
    public static function getEstados()
    {
        return [
            self::ESTADO_ACTIVO => 'Activo',
            self::ESTADO_INACTIVO => 'Inactivo(Sin Asignar)',
            self::ESTADO_DANADO => 'dañado(Proceso de baja)',
            self::ESTADO_MANTENIMIENTO => 'En Mantenimiento',
            self::ESTADO_BAJA => 'BAJA',
        ];
    }

    /**
     * Obtener equipos con estado dañado (proceso de baja)
     */
    public static function getEquiposDanados()
    {
        return self::find()->where(['Estado' => 'dañado(Proceso de baja)'])->all();
    }

    /**
     * Contar equipos con estado dañado (proceso de baja)
     */
    public static function countEquiposDanados()
    {
        return self::find()->where(['Estado' => 'dañado(Proceso de baja)'])->count();
    }

    /**
     * Obtiene los tipos de equipo disponibles
     */
    public static function getTipos()
    {
        return [
            self::TIPO_PC => 'PC',
            self::TIPO_LAPTOP => 'Laptop',
            self::TIPO_SERVIDOR => 'Servidor',
            self::TIPO_OTRO => 'Otro',
        ];
    }

    /**
     * Obtiene las opciones de ubicación de edificios
     */
    public static function getUbicacionesEdificio()
    {
        return [
            'A' => 'Edificio A',
            'B' => 'Edificio B',
            'C' => 'Edificio C',
            'D' => 'Edificio D',
            'E' => 'Edificio E',
            'F' => 'Edificio F',
            'G' => 'Edificio G',
            'H' => 'Edificio H',
            'I' => 'Edificio I',
            'J' => 'Edificio J',
            'K' => 'Edificio K',
            'L' => 'Edificio L',
            'M' => 'Edificio M',
            'N' => 'Edificio N',
            'O' => 'Edificio O',
            'P' => 'Edificio P',
            'Q' => 'Edificio Q',
            'R' => 'Edificio R',
            'S' => 'Edificio S',
            'T' => 'Edificio T',
            'U' => 'Edificio U',
        ];
    }

    /**
     * Obtiene todos los equipos disponibles
     * @return array
     */
    public static function getEquiposDisponibles()
    {
        return self::find()->all();
    }

    /**
     * Obtiene solo equipos activos
     * @return array
     */
    public static function getEquiposActivos()
    {
        return self::find()->where(['Estado' => self::ESTADO_ACTIVO])->all();
    }

    /**
     * Obtiene solo equipos inactivos
     * @return array
     */
    public static function getEquiposInactivos()
    {
        return self::find()->where(['Estado' => self::ESTADO_INACTIVO])->all();
    }

    /**
     * Busca un equipo por número de serie
     * @param string $numSerie
     * @return Equipo|null
     */
    public static function findByNumSerie($numSerie)
    {
        return self::findOne(['NUM_SERIE' => $numSerie]);
    }

    /**
     * Busca un equipo por número de inventario
     * @param string $numInventario
     * @return Equipo|null
     */
    public static function findByNumInventario($numInventario)
    {
        return self::findOne(['NUM_INVENTARIO' => $numInventario]);
    }

    /**
     * Obtiene el nombre completo del equipo
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->MARCA . ' ' . $this->MODELO . ' (' . $this->NUM_SERIE . ')';
    }

    /**
     * Verifica si el equipo tiene todos los campos requeridos
     * @return bool
     */
    public function estaCompleto()
    {
        return !empty($this->CPU) && !empty($this->DD) && !empty($this->RAM) && 
               !empty($this->MARCA) && !empty($this->MODELO) && !empty($this->NUM_SERIE);
    }

    /**
     * Verifica si el equipo está activo
     * @return bool
     */
    public function estaActivo()
    {
        return $this->Estado === self::ESTADO_ACTIVO;
    }

    /**
     * Verifica si el equipo está inactivo
     * @return bool
     */
    public function estaInactivo()
    {
        return $this->Estado === self::ESTADO_INACTIVO;
    }

    /**
     * Cambia el estado del equipo a activo
     * @return bool
     */
    public function activar()
    {
        $this->Estado = self::ESTADO_ACTIVO;
        return $this->save();
    }

    /**
     * Cambia el estado del equipo a inactivo
     * @return bool
     */
    public function desactivar()
    {
        $this->Estado = self::ESTADO_INACTIVO;
        return $this->save();
    }

    /**
     * Obtiene el estado formateado para mostrar
     * @return string
     */
    public function getEstadoTexto()
    {
        $estados = self::getEstados();
        return isset($estados[$this->Estado]) ? $estados[$this->Estado] : $this->Estado;
    }

    /**
     * Obtiene los días que lleva activo el equipo desde la emisión
     * @return int
     */
    public function getDiasActivo()
    {
        if (empty($this->EMISION_INVENTARIO)) {
            return 0;
        }
        
        try {
            // Limpiar y validar la fecha
            $fechaStr = trim($this->EMISION_INVENTARIO);
            if (empty($fechaStr)) {
                return 0;
            }
            
            $fechaEmision = new \DateTime($fechaStr);
            $fechaActual = new \DateTime();
            
            // Usar el mismo cálculo que JavaScript: diferencia en milisegundos
            $diferenciaMilisegundos = $fechaActual->getTimestamp() - $fechaEmision->getTimestamp();
            $dias = floor($diferenciaMilisegundos / (60 * 60 * 24));
            
            // Verificar que la fecha no esté en el futuro
            if ($dias < 0) {
                return 0;
            }
            
            return $dias;
            
        } catch (\Exception $e) {
            // Log del error para debugging
            error_log("Error calculando días activo para equipo {$this->idEQUIPO}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene los años que lleva activo el equipo desde la emisión
     * @return float
     */
    public function getAnosActivo()
    {
        $dias = $this->getDiasActivo();
        if ($dias == 0) {
            return 0;
        }
        // Usar exactamente la misma división que JavaScript: 365.25
        return round($dias / 365.25, 2);
    }

    /**
     * Obtiene el tiempo activo formateado (días y años)
     * @return string
     */
    public function getTiempoActivoFormateado()
    {
        $dias = $this->getDiasActivo();
        $anos = $this->getAnosActivo();
        
        if ($dias == 0) {
            return 'Sin fecha de emisión';
        }
        
        if ($anos >= 1) {
            return sprintf('%d días (%s años)', $dias, $anos);
        } else {
            return sprintf('%d días', $dias);
        }
    }

    /**
     * Obtiene solo el texto de años activo
     * @return string
     */
    public function getAnosActivoTexto()
    {
        $dias = $this->getDiasActivo();
        
        if ($dias == 0) {
            return 'Sin fecha';
        }
        
        $anos = $this->getAnosActivo();
        
        if ($anos < 1) {
            return 'Menos de 1 año';
        } else if ($anos == 1) {
            return '1 año';
        } else {
            return sprintf('%.1f años', $anos);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->Estado)) {
                $this->Estado = self::ESTADO_ACTIVO;
            }
            
            // Configurar el editor actual
            $this->ultimo_editor = $this->getCurrentUser();
            
            return true;
        }
        return false;
    }

    // Agregar este método para asegurar el valor por defecto
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            if (empty($this->Estado)) {
                $this->Estado = self::ESTADO_ACTIVO;
            }
            if (empty($this->tipoequipo)) {
                $this->tipoequipo = self::TIPO_PC;
            }
            if (empty($this->EMISION_INVENTARIO)) {
                $this->EMISION_INVENTARIO = date('Y-m-d');
            }
            if (empty($this->DD2)) {
                $this->DD2 = 'NO';
            }
            if (empty($this->DD3)) {
                $this->DD3 = 'NO';
            }
            if (empty($this->DD4)) {
                $this->DD4 = 'NO';
            }
            if (empty($this->RAM2)) {
                $this->RAM2 = 'NO';
            }
            if (empty($this->RAM3)) {
                $this->RAM3 = 'NO';
            }
            if (empty($this->RAM4)) {
                $this->RAM4 = 'NO';
            }
        }
    }

    /**
     * Obtiene el usuario actual que está logueado
     */
    private function getCurrentUser()
    {
        // Verificar si Yii está inicializado y hay un usuario logueado
        if (isset(\Yii::$app) && !\Yii::$app->user->isGuest) {
            // Obtener el usuario logueado actual
            $user = \Yii::$app->user->identity;
            if ($user && isset($user->username)) {
                return $user->username;
            }
        }
        
        // Si no hay usuario logueado, usar 'Sistema' como fallback
        return 'Sistema';
    }

    /**
     * Obtiene información completa del usuario que editó
     */
    public function getInfoUsuarioEditor()
    {
        if (empty($this->ultimo_editor)) {
            return [
                'username' => 'Sistema',
                'email' => null,
                'id' => null,
                'display_name' => 'Sistema'
            ];
        }
        
        // Si es 'Sistema', retornar tal como está
        if ($this->ultimo_editor === 'Sistema') {
            return [
                'username' => 'Sistema',
                'email' => null,
                'id' => null,
                'display_name' => 'Sistema'
            ];
        }
        
        // Buscar información del usuario usando la relación
        $usuario = $this->usuarioEditor;
        if ($usuario) {
            return [
                'username' => $usuario->username,
                'email' => $usuario->email,
                'id' => $usuario->id,
                'display_name' => $usuario->username . ' (' . $usuario->email . ')'
            ];
        }
        
        return [
            'username' => $this->ultimo_editor,
            'email' => null,
            'id' => null,
            'display_name' => $this->ultimo_editor
        ];
    }

    /**
     * Relación con la tabla user para obtener datos del editor
     */
    public function getUsuarioEditor()
    {
        return $this->hasOne(\common\models\User::class, ['username' => 'ultimo_editor']);
    }

    /**
     * Obtiene información detallada del último editor
     */
    public function getInfoUltimaEdicion()
    {
        if (empty($this->fecha_ultima_edicion)) {
            return 'Sin ediciones registradas';
        }
        
        $userInfo = $this->getInfoUsuarioEditor();
        
        $fecha = new \DateTime($this->fecha_ultima_edicion);
        $ahora = new \DateTime();
        $diferencia = $ahora->diff($fecha);
        
        $tiempo = '';
        if ($diferencia->days > 0) {
            $tiempo = $diferencia->days . ' día' . ($diferencia->days > 1 ? 's' : '');
        } elseif ($diferencia->h > 0) {
            $tiempo = $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
        } else {
            $tiempo = $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
        }
        
        return "Editado por {$userInfo['display_name']} hace {$tiempo}";
    }
}