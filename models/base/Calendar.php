<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%calendar}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Calendar.
*
    * @property integer $id
    * @property integer $structure_id
    * @property string $fecha_ini
    * @property string $fecha_fin
    * @property string $notes
    *
            * @property \app\models\Structure $structure
    */
abstract class Calendar extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['structure_id', 'fecha_ini', 'fecha_fin'], 'required'],
            [['structure_id'], 'integer'],
            [['fecha_ini', 'fecha_fin'], 'safe'],
            [['notes'], 'string'],
            [['structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structure::className(), 'targetAttribute' => ['structure_id' => 'id']]
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
    'fecha_ini' => 'Fecha Ini',
    'fecha_fin' => 'Fecha Fin',
    'notes' => 'Notes',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getStructure()
    {
    return $this->hasOne(\app\models\Structure::className(), ['id' => 'structure_id']);
    }
}
