<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%sql_event_sex}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\SqlEventSex.
*
    * @property string $m
    * @property string $f
    * @property string $total
    * @property string $activity
    * @property integer $id
    * @property integer $structure_id
    * @property string $name
    * @property string $title
    * @property string $organizer
    * @property string $text
    * @property string $start
    * @property string $end
    * @property string $place
    * @property string $monitor
    * @property string $notes
    * @property integer $activity_id
*/
abstract class SqlEventSex extends \app\components\ActiveRecord
{
        /**
    * @inheritdoc
    * @return SqlEventSexQuery the active query used by this AR class.
    */
    public static function find()
    {
    return new SqlEventSexQuery(get_called_class());
    }

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['m', 'f', 'total', 'id', 'structure_id', 'activity_id'], 'integer'],
            [['activity', 'name', 'text', 'monitor', 'activity_id'], 'required'],
            [['activity', 'title', 'text', 'notes'], 'string'],
            [['start', 'end'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['organizer', 'place'], 'string', 'max' => 200],
            [['monitor'], 'string', 'max' => 5]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'm' => 'M',
    'f' => 'F',
    'total' => 'Total',
    'activity' => 'Activity',
    'id' => 'ID',
    'structure_id' => 'Structure ID',
    'name' => 'Name',
    'title' => 'Title',
    'organizer' => 'Organizer',
    'text' => 'Text',
    'start' => 'Start',
    'end' => 'End',
    'place' => 'Place',
    'monitor' => 'Monitor',
    'notes' => 'Notes',
    'activity_id' => 'Activity ID',
];
}
}
