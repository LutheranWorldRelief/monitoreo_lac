<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "sql_debug_contact_name".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlDebugContactName.
 *
 * @property string $name
 * @property int    $cuenta
 */
abstract class SqlDebugContactName extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sql_debug_contact_name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cuenta'], 'integer'],
            [['name'], 'string', 'max' => 510],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'cuenta' => Yii::t('app', 'Cuenta'),
        ];
    }
}
