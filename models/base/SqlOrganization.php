<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%sql_organization}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\SqlOrganization.
*
    * @property integer $id
    * @property string $name
    * @property string $country
*/
abstract class SqlOrganization extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['country'], 'string', 'max' => 2]
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
    'country' => 'Country',
];
}
}
