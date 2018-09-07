<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%sql_debug_contact_doc}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\SqlDebugContactDoc.
*
    * @property string $doc_id
    * @property string $cuenta
*/
abstract class SqlDebugContactDoc extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['cuenta'], 'integer'],
            [['doc_id'], 'string', 'max' => 40]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'doc_id' => 'Doc ID',
    'cuenta' => 'Cuenta',
];
}
}
