<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%sql_contact_event}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\SqlContactEvent.
*
    * @property integer $contact_id
    * @property string $name
    * @property string $country
    * @property string $org_name
    * @property string $type_name
    * @property string $event
    * @property integer $event_id
    * @property string $organizer
    * @property string $start
    * @property string $end
    * @property string $place
*/
abstract class SqlContactEvent extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['contact_id', 'event_id'], 'integer'],
            [['type_name'], 'string'],
            [['event'], 'required'],
            [['start', 'end'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 2],
            [['org_name'], 'string', 'max' => 50],
            [['event'], 'string', 'max' => 100],
            [['organizer', 'place'], 'string', 'max' => 200]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'contact_id' => 'Contact ID',
    'name' => 'Name',
    'country' => 'Country',
    'org_name' => 'Org Name',
    'type_name' => 'Type Name',
    'event' => 'Event',
    'event_id' => 'Event ID',
    'organizer' => 'Organizer',
    'start' => 'Start',
    'end' => 'End',
    'place' => 'Place',
];
}
}
