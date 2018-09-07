<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%contact_worked_with}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\ContactWorkedWith.
*
    * @property integer $id
    * @property integer $from_contact_id
    * @property integer $to_contact_id
*/
abstract class ContactWorkedWith extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['from_contact_id', 'to_contact_id'], 'required'],
            [['from_contact_id', 'to_contact_id'], 'integer']
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'from_contact_id' => 'From Contact ID',
    'to_contact_id' => 'To Contact ID',
];
}
}
