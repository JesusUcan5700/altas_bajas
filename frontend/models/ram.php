<?php

namespace frontend\models;

use Yii;
use DateTime;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\User;

/**
 * This is the model class for table "memoria_ram".
 *
 * @property int $idRAM
 * @property string $MARCA
 * @property string $MODELO
 * @property string $CAPACIDAD
 * @property string $TIPO_INTERFAZ
 * @property string $TIPO_DDR
 * @property string $ESTADO
 * @property string $FECHA
 * @property string|null $numero_serie
 * @property string|null $numero_inventario
 * @property string|null $Descripcion
 * @property string|null $ubicacion_edificio
 * @property string|null $ubicacion_detalle
 * @property string|null $fecha_creacion
 * @property string|null $fecha_ultima_edicion
 * @property string|null $ultimo_editor
 *
 * @property User $ultimoEditor
 */
class Ram extends \yii\db\ActiveRecord
{
    // Constantes de estados estandarizados
    const ESTADO_ACTIVO = 'Activo';
    const ESTADO_INACTIVO = 'Inactivo(Sin Asignar)';
    const ESTADO_DAÑADO = 'dañado(Proceso de baja)';
    const ESTADO_MANTENIMIENTO = 'En Mantenimiento';
    const ESTADO_BAJA = 'BAJA';

    // Tipos de DDR
    const DDR_DDR3 = 'DDR3';
    const DDR_DDR4 = 'DDR4';
    const DDR_DDR5 = 'DDR5';

    // Tipos de interfaz
    const INTERFAZ_DIMM = 'DIMM';
    const INTERFAZ_SO_DIMM = 'SO-DIMM';
    const INTERFAZ_MICRO_DIMM = 'Micro-DIMM';
    const INTERFAZ_OTRO = 'Otro';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'fecha_creacion',
                'updatedAtAttribute' => 'fecha_ultima_edicion',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
                'skipUpdateOnClean' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Guardar el usuario que está editando
            if (!Yii::$app->user->isGuest) {
                $this->ultimo_editor = Yii::$app->user->identity->username;
            } else {
                $this->ultimo_editor = 'Sistema';
            }
            
            // Para inserciones, asegurar que fecha_creacion se establezca
            if ($insert && empty($this->fecha_creacion)) {
                $this->fecha_creacion = date('Y-m-d H:i:s');
            }
            
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'memoria_ram';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Campos requeridos
            [['MARCA', 'MODELO', 'CAPACIDAD', 'TIPO_INTERFAZ', 'TIPO_DDR'], 'required'],
            
            // Validación de fecha solo para FECHA (no para campos de auditoría)
            [['FECHA'], 'date', 'format' => 'php:Y-m-d'],
            
            // Validaciones de longitud
            [['MARCA', 'MODELO'], 'string', 'max' => 45],
            [['CAPACIDAD', 'TIPO_INTERFAZ'], 'string', 'max' => 20],
            [['TIPO_DDR', 'ESTADO'], 'string', 'max' => 100],
            [['numero_serie', 'numero_inventario'], 'string', 'max' => 15],
            [['Descripcion'], 'string', 'max' => 100],
            [['ubicacion_edificio', 'ubicacion_detalle'], 'string', 'max' => 255],
            [['ultimo_editor'], 'string', 'max' => 100],
            
            // Validación de estados permitidos
            [['ESTADO'], 'in', 'range' => [
                self::ESTADO_ACTIVO,
                self::ESTADO_INACTIVO,
                self::ESTADO_DAÑADO,
                self::ESTADO_MANTENIMIENTO,
                self::ESTADO_BAJA
            ]],
            
            // Campos de auditoría - solo marcados como seguros, sin validación
            [['fecha_creacion', 'fecha_ultima_edicion'], 'safe'],
            
            // Otros campos opcionales
            [['FECHA', 'ESTADO', 'numero_serie', 'numero_inventario', 'Descripcion', 'ubicacion_edificio', 'ubicacion_detalle'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idRAM' => 'ID RAM',
            'MARCA' => 'Marca',
            'MODELO' => 'Modelo',
            'CAPACIDAD' => 'Capacidad',
            'TIPO_INTERFAZ' => 'Tipo de Interfaz',
            'TIPO_DDR' => 'Tipo DDR',
            'ESTADO' => 'Estado',
            'FECHA' => 'Fecha',
            'numero_serie' => 'Número de Serie',
            'numero_inventario' => 'Número de Inventario',
            'Descripcion' => 'Descripción',
            'ubicacion_edificio' => 'Edificio',
            'ubicacion_detalle' => 'Detalle de Ubicación',
            'fecha_creacion' => 'Fecha Creación',
            'fecha_ultima_edicion' => 'Fecha Última Edición',
            'ultimo_editor' => 'Último Editor',
        ];
    }

    /**
     * Relación con el usuario que editó por última vez
     */
    public function getUltimoEditor()
    {
        return $this->hasOne(User::class, ['username' => 'ultimo_editor']);
    }

    /**
     * Obtener información del último editor
     */
    public function getInfoUltimoEditor()
    {
        if ($this->ultimoEditor) {
            return $this->ultimoEditor->email . ' (' . $this->ultimoEditor->username . ')';
        }
        return $this->ultimo_editor ?: 'Sistema';
    }

    /**
     * Obtener fecha de creación formateada
     */
    public function getFechaCreacionFormateada()
    {
        return $this->fecha_creacion ? Yii::$app->formatter->asDatetime($this->fecha_creacion) : '';
    }

    /**
     * Obtener fecha de última edición formateada
     */
    public function getFechaUltimaEdicionFormateada()
    {
        return $this->fecha_ultima_edicion ? Yii::$app->formatter->asDatetime($this->fecha_ultima_edicion) : '';
    }

    /**
     * Obtener tiempo activo desde la creación
     */
    public function getTiempoActivo()
    {
        // Usar FECHA del usuario si está disponible, sino fecha_creacion del sistema
        $fechaBase = $this->FECHA ?: $this->fecha_creacion;
        
        if (!$fechaBase) {
            return 'No definido';
        }
        
        $fechaCreacion = new \DateTime($fechaBase);
        $ahora = new \DateTime();
        $diferencia = $ahora->diff($fechaCreacion);
        
        $partes = [];
        
        if ($diferencia->y > 0) {
            $partes[] = $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '');
        }
        if ($diferencia->m > 0) {
            $partes[] = $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
        }
        if ($diferencia->d > 0) {
            $partes[] = $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
        }
        if (empty($partes)) {
            if ($diferencia->h > 0) {
                $partes[] = $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
            }
            if ($diferencia->i > 0) {
                $partes[] = $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
            }
            if (empty($partes)) {
                return 'Menos de 1 minuto';
            }
        }
        
        return implode(', ', array_slice($partes, 0, 2));
    }

    /**
     * Obtener tiempo desde la última edición
     */
    public function getTiempoUltimaEdicion()
    {
        if (!$this->fecha_ultima_edicion) {
            return 'No definido';
        }
        
        $fechaEdicion = new \DateTime($this->fecha_ultima_edicion);
        $ahora = new \DateTime();
        $diferencia = $ahora->diff($fechaEdicion);
        
        $partes = [];
        
        if ($diferencia->y > 0) {
            $partes[] = $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '');
        }
        if ($diferencia->m > 0) {
            $partes[] = $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
        }
        if ($diferencia->d > 0) {
            $partes[] = $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
        }
        if (empty($partes)) {
            if ($diferencia->h > 0) {
                $partes[] = $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
            }
            if ($diferencia->i > 0) {
                $partes[] = $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
            }
            if (empty($partes)) {
                return 'Hace menos de 1 minuto';
            }
        }
        
        return 'Hace ' . implode(', ', array_slice($partes, 0, 2));
    }

    /**
     * Establece valores por defecto
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->ESTADO = self::ESTADO_ACTIVO;
        }
    }

    /**
     * Establece valores por defecto antes de validar
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (empty($this->FECHA)) {
                $this->FECHA = date('Y-m-d');
            }
            
            if (empty($this->ESTADO)) {
                $this->ESTADO = self::ESTADO_ACTIVO;
            }
            
            return true;
        }
        return false;
    }

    /**
     * Obtiene las marcas disponibles
     */
    public static function getMarcas()
    {
        return [
            'Corsair' => 'Corsair',
            'Kingston' => 'Kingston',
            'Crucial' => 'Crucial',
            'G.Skill' => 'G.Skill',
            'ADATA' => 'ADATA',
            'Samsung' => 'Samsung',
            'Hynix' => 'Hynix',
            'Otra' => 'Otra'
        ];
    }

    /**
     * Obtiene los tipos de DDR disponibles
     */
    public static function getTiposDDR()
    {
        return [
            self::DDR_DDR3 => 'DDR3',
            self::DDR_DDR4 => 'DDR4',
            self::DDR_DDR5 => 'DDR5',
        ];
    }

    /**
     * Obtiene los tipos de interfaz disponibles
     */
    public static function getTiposInterfaz()
    {
        return [
            self::INTERFAZ_DIMM => 'DIMM',
            self::INTERFAZ_SO_DIMM => 'SO-DIMM',
            self::INTERFAZ_MICRO_DIMM => 'Micro-DIMM',
            self::INTERFAZ_OTRO => 'Otro',
        ];
    }

    /**
     * Obtiene los estados disponibles estandarizados
     */
    public static function getEstados()
    {
        return [
            self::ESTADO_ACTIVO => 'Activo',
            self::ESTADO_INACTIVO => 'Inactivo(Sin Asignar)',
            self::ESTADO_DAÑADO => 'dañado(Proceso de baja)',
            self::ESTADO_MANTENIMIENTO => 'En Mantenimiento',
            self::ESTADO_BAJA => 'BAJA',
        ];
    }

    /**
     * Obtener equipos con estado dañado (proceso de baja)
     */
    public static function getEquiposDanados()
    {
        return self::find()->where(['ESTADO' => 'dañado(Proceso de baja)'])->all();
    }

    /**
     * Contar equipos con estado dañado (proceso de baja)
     */
    public static function countEquiposDanados()
    {
        return self::find()->where(['ESTADO' => 'dañado(Proceso de baja)'])->count();
    }

    /**
     * Obtiene los edificios disponibles (A-U)
     * @return array
     */
    public static function getEdificios()
    {
        $edificios = [];
        for ($i = ord('A'); $i <= ord('U'); $i++) {
            $letra = chr($i);
            $edificios[$letra] = "Edificio $letra";
        }
        return $edificios;
    }
}