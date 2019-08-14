<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "sql_usuario".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlUsuario.
 *
 * @property int    $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $is_active
 * @property string $is_superuser
 */
abstract class SqlUsuario extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sql_usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username'], 'required'],
            [['username'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 61],
            [['email'], 'string', 'max' => 254],
            [['is_active', 'is_superuser'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_superuser' => Yii::t('app', 'Is Superuser'),
        ];
    }
}
