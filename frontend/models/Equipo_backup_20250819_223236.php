<?php

namespace frontend\models;

use Yii;

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
 * @property string $fecha
 * @property string $descripcion
 * @property string $ultimo_editor
 * @property string $fecha_ultima_edicion
 */
class Equipo extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVO = 'activo';
    const ESTADO_INACTIVO = 'Inactivo(Sin Asignar)';
    const ESTADO_DANADO = 'dañado(Proceso de baja)';

    const ESTADO_MANTENIMIENTO = 'mantenimiento';

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
            [['fecha_ultima_edicion'], 'datetime'],
            [['fecha'], 'date', 'format' => 'yyyy-MM-dd'],
            [['NUM_SERIE'], 'unique'],
            [['NUM_INVENTARIO'], 'unique'],
            [['Estado'], 'string'],
            [['Estado'], 'in', 'range' => [
                self::ESTADO_ACTIVO, 
                self::ESTADO_INACTIVO, 
                self::ESTADO_DANADO,  
                self::ESTADO_MANTENIMIENTO
            ]],
            [['Estado'], 'default', 'value' => self::ESTADO_ACTIVO],
            [['tipoequipo'], 'default', 'value' => self::TIPO_PC],
            [['fecha'], 'default', 'value' => date('Y-m-d')],
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
            'fecha' => 'Fecha de Registro',
            'descripcion' => 'Descripción',
            'ultimo_editor' => 'Último Editor',
            'fecha_ultima_edicion' => 'Fecha de Última Edición',
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
        ];
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
            $fechaEmision = new \DateTime($this->EMISION_INVENTARIO);
            $fechaActual = new \DateTime();
            $diferencia = $fechaActual->diff($fechaEmision);
            
            return $diferencia->days;
        } catch (\Exception $e) {
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
        return round($dias / 365.25, 2); // 365.25 para considerar años bisiestos
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
        $anos = $this->getAnosActivo();
        
        if ($anos == 0) {
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
            
            // Registrar información del editor solo si no es un registro nuevo
            if (!$insert) {
                // Obtener el usuario actual (puedes ajustar esto según tu sistema de autenticación)
                if (Yii::$app->user->identity) {
                    $this->ultimo_editor = Yii::$app->user->identity->username ?? 'Usuario desconocido';
                } else {
                    $this->ultimo_editor = 'Sistema';
                }
                $this->fecha_ultima_edicion = date('Y-m-d H:i:s');
            }
            
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
            if (empty($this->fecha)) {
                $this->fecha = date('Y-m-d');
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
     * Obtiene la fecha de última edición formateada
     * @return string
     */
    public function getFechaUltimaEdicionFormateada()
    {
        if (empty($this->fecha_ultima_edicion)) {
            return 'No editado';
        }
        
        $fecha = new \DateTime($this->fecha_ultima_edicion);
        $ahora = new \DateTime();
        $diferencia = $ahora->diff($fecha);
        
        if ($diferencia->days == 0) {
            return 'Hoy a las ' . $fecha->format('H:i');
        } elseif ($diferencia->days == 1) {
            return 'Ayer a las ' . $fecha->format('H:i');
        } elseif ($diferencia->days < 7) {
            return 'Hace ' . $diferencia->days . ' días';
        } else {
            return $fecha->format('d/m/Y H:i');
        }
    }

    /**
     * Obtiene información completa del último editor
     * @return string
     */
    public function getInfoUltimoEditor()
    {
        if (empty($this->ultimo_editor) && empty($this->fecha_ultima_edicion)) {
            return 'Sin ediciones registradas';
        }
        
        $editor = $this->ultimo_editor ?: 'Usuario desconocido';
        $fecha = $this->getFechaUltimaEdicionFormateada();
        
        return $editor . ' - ' . $fecha;
    }

    /**
     * Hook que se ejecuta antes de guardar el modelo
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Solo actualizar información de edición si no es un nuevo registro
        if (!$insert) {
            // Obtener el nombre del usuario autenticado
            $user = Yii::$app->user->identity;
            if ($user) {
                $this->ultimo_editor = $user->username;
            } else {
                $this->ultimo_editor = 'Sistema';
            }
            
            // Establecer la fecha y hora actual
            $this->fecha_ultima_edicion = date('Y-m-d H:i:s');
        }

        return true;
    }
}