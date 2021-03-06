<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "{{%sql_usuarios}}".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlUsuarios.
 *
 * @property integer $id
 * @property string  $password
 * @property string  $last_login
 * @property string  $is_superuser
 * @property string  $username
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $email
 * @property string  $is_active
 * @property string  $activo
 * @property string  $nombre
 */
abstract class SqlUsuarios extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'password', 'is_superuser', 'username', 'first_name', 'last_name', 'email', 'is_active'], 'required'],
            [['id'], 'integer'],
            [['last_login'], 'safe'],
            [['password'], 'string', 'max' => 128],
            [['is_superuser', 'is_active'], 'string', 'max' => 5],
            [['username', 'first_name', 'last_name'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 254],
            [['activo'], 'string', 'max' => 2],
            [['nombre'], 'string', 'max' => 61]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'password' => Yii::t('app', 'Password'),
            'last_login' => Yii::t('app', 'Last Login'),
            'is_superuser' => Yii::t('app', 'Is Superuser'),
            'username' => Yii::t('app', 'Username'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'is_active' => Yii::t('app', 'Is Active'),
            'activo' => Yii::t('app', 'Activo'),
            'nombre' => Yii::t('app', 'Nombre'),
        ];
    }
}
