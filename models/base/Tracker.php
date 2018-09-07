<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%tracker}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Tracker.
*
    * @property integer $id
    * @property integer $structure_id
    * @property string $linea_base
    * @property double $meta
    * @property string $um
    * @property string $fuente_datos
    * @property string $metodo
    * @property string $frecuencia
    * @property string $responsabilidad
    * @property integer $peso
    * @property string $notes
    * @property integer $sub_tracker_id
    * @property integer $type
    * @property string $fecha_ini
    * @property string $fecha_fin
    * @property integer $hombres
    * @property integer $mujeres
    *
            * @property \app\models\Structure $structure
            * @property \app\models\Tracker $subTracker
            * @property \app\models\Tracker[] $trackers
    */
abstract class Tracker extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['structure_id', 'meta', 'sub_tracker_id'], 'required'],
            [['structure_id', 'peso', 'sub_tracker_id', 'type', 'hombres', 'mujeres'], 'integer'],
            [['meta'], 'number'],
            [['notes'], 'string'],
            [['fecha_ini', 'fecha_fin'], 'safe'],
            [['linea_base'], 'string', 'max' => 45],
            [['um', 'fuente_datos', 'metodo', 'frecuencia', 'responsabilidad'], 'string', 'max' => 255],
            [['structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structure::className(), 'targetAttribute' => ['structure_id' => 'id']],
            [['sub_tracker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tracker::className(), 'targetAttribute' => ['sub_tracker_id' => 'id']]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'structure_id' => 'Structure ID',
    'linea_base' => 'Linea Base',
    'meta' => 'Meta',
    'um' => 'Um',
    'fuente_datos' => 'Fuente Datos',
    'metodo' => 'Metodo',
    'frecuencia' => 'Frecuencia',
    'responsabilidad' => 'Responsabilidad',
    'peso' => 'Peso',
    'notes' => 'Notes',
    'sub_tracker_id' => 'Sub Tracker ID',
    'type' => 'Type',
    'fecha_ini' => 'Fecha Ini',
    'fecha_fin' => 'Fecha Fin',
    'hombres' => 'Hombres',
    'mujeres' => 'Mujeres',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getStructure()
    {
    return $this->hasOne(\app\models\Structure::className(), ['id' => 'structure_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSubTracker()
    {
    return $this->hasOne(\app\models\Tracker::className(), ['id' => 'sub_tracker_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTrackers()
    {
    return $this->hasMany(\app\models\Tracker::className(), ['sub_tracker_id' => 'id']);
    }
}
