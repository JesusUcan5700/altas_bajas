<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "baterias".
 *
 * @property int $idBateria
 * @property string $MARCA
 * @property string $MODELO
 * @property string $TIPO
 * @property string $FORMATO_PILA
 * @property string $VOLTAJE
 * @property string|null $CAPACIDAD
 * @property string|null $USO
 * @property int $RECARGABLE
 * @property string $NUMERO_INVENTARIO
 * @property string|null $DESCRIPCION
 * @property string $ESTADO
 * @property string $FECHA
 * @property string|null $FECHA_VENCIMIENTO
 * @property string|null $FECHA_REEMPLAZO
 * @property string|null $ubicacion_edificio Ubicación completa del equipo (edificio, piso, oficina, etc.)
 * @property string|null $ubicacion_detalle Descripción detallada de la ubicación del equipo
 * @property string|null $USO_PERSONALIZADO
 * @property string|null $NUMERO_SERIE
 * @property string|null $fecha_creacion
 * @property string|null $fecha_ultima_edicion
 * @property string|null $ultimo_editor
 */
class Bateria extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'baterias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CAPACIDAD', 'USO', 'DESCRIPCION', 'FECHA_VENCIMIENTO', 'FECHA_REEMPLAZO', 'ubicacion_edificio', 'ubicacion_detalle', 'USO_PERSONALIZADO', 'NUMERO_SERIE', 'fecha_creacion', 'fecha_ultima_edicion', 'ultimo_editor'], 'default', 'value' => null],
            [['RECARGABLE'], 'default', 'value' => 0],
            [['MARCA', 'MODELO', 'TIPO', 'FORMATO_PILA', 'VOLTAJE', 'NUMERO_INVENTARIO', 'ESTADO', 'FECHA'], 'required'],
            [['RECARGABLE'], 'integer'],
            [['FECHA', 'FECHA_VENCIMIENTO', 'FECHA_REEMPLAZO', 'fecha_creacion', 'fecha_ultima_edicion'], 'safe'],
            [['MARCA', 'MODELO', 'USO', 'NUMERO_INVENTARIO'], 'string', 'max' => 45],
            [['TIPO', 'CAPACIDAD'], 'string', 'max' => 20],
            [['FORMATO_PILA', 'VOLTAJE'], 'string', 'max' => 10],
            [['DESCRIPCION', 'USO_PERSONALIZADO', 'ultimo_editor'], 'string', 'max' => 100],
            [['ESTADO'], 'string', 'max' => 15],
            [['ubicacion_edificio', 'ubicacion_detalle'], 'string', 'max' => 255],
            [['NUMERO_SERIE'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idBateria' => 'Id Bateria',
            'MARCA' => 'Marca',
            'MODELO' => 'Modelo',
            'TIPO' => 'Tipo',
            'FORMATO_PILA' => 'Formato Pila',
            'VOLTAJE' => 'Voltaje',
            'CAPACIDAD' => 'Capacidad',
            'USO' => 'Uso',
            'RECARGABLE' => 'Recargable',
            'NUMERO_INVENTARIO' => 'Numero Inventario',
            'DESCRIPCION' => 'Descripcion',
            'ESTADO' => 'Estado',
            'FECHA' => 'Fecha',
            'FECHA_VENCIMIENTO' => 'Fecha Vencimiento',
            'FECHA_REEMPLAZO' => 'Fecha Reemplazo',
            'ubicacion_edificio' => 'Ubicacion Edificio',
            'ubicacion_detalle' => 'Ubicacion Detalle',
            'USO_PERSONALIZADO' => 'Uso Personalizado',
            'NUMERO_SERIE' => 'Numero Serie',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_ultima_edicion' => 'Fecha Ultima Edicion',
            'ultimo_editor' => 'Ultimo Editor',
        ];
    }

}
