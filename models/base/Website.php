<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%website}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Website.
*
    * @property integer $id
    * @property string $website
    * @property string $type
    * @property string $public_visible
    * @property string $contact_visible
    * @property integer $contact_id
*/
abstract class Website extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['website', 'type', 'public_visible', 'contact_visible', 'contact_id'], 'required'],
            [['contact_id'], 'integer'],
            [['website'], 'string', 'max' => 200],
            [['type'], 'string', 'max' => 20],
            [['public_visible', 'contact_visible'], 'string', 'max' => 5]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'website' => 'Website',
    'type' => 'Type',
    'public_visible' => 'Public Visible',
    'contact_visible' => 'Contact Visible',
    'contact_id' => 'Contact ID',
];
}
}
