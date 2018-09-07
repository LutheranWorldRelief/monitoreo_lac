<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%monitor}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Monitor.
*
    * @property integer $id
    * @property string $name
*/
abstract class Monitor extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 40]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'name' => 'Name',
];
}
}
