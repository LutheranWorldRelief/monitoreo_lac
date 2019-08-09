<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "auth_user".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\AuthUser.
 *
 * @property int    $id
 * @property string $password
 * @property string $last_login
 * @property int    $is_superuser
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int    $is_staff
 * @property int    $is_active
 * @property string $date_joined
 * @property string $access_token
 * @property array  $countries
 * @property array  $projects
 */
abstract class AuthUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_user_yii';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'username', 'first_name', 'is_staff', 'is_active', 'date_joined'], 'required'],
            [['last_login', 'date_joined', 'countries', 'projects'], 'safe'],
            [['is_superuser', 'is_staff', 'is_active'], 'boolean'],
            [['access_token'], 'string'],
            [['password'], 'string', 'max' => 128],
            [['username', 'first_name', 'last_name'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 254],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'last_login' => 'Last Login',
            'is_superuser' => 'Is Superuser',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'is_staff' => 'Is Staff',
            'is_active' => 'Is Active',
            'date_joined' => 'Date Joined',
            'access_token' => 'Access Token',
            'countries' => 'Countries',
            'projects' => 'Projects',
        ];
    }
}
