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
 * @property string  $language
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
            [['access_token','language'], 'string'],
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
            'id' => Yii::t('app', 'ID'),
            'password' => Yii::t('app', 'Password'),
            'last_login' => Yii::t('app', 'Last Login'),
            'is_superuser' => Yii::t('app', 'Is Superuser'),
            'username' => Yii::t('app', 'Username'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'is_staff' => Yii::t('app', 'Is Staff'),
            'is_active' => Yii::t('app', 'Is Active'),
            'date_joined' => Yii::t('app', 'Date Joined'),
            'access_token' => Yii::t('app', 'Access Token'),
            'countries' => Yii::t('app', 'Countries'),
            'projects' => Yii::t('app', 'Projects'),
            'language' => Yii::t('app', 'Language'),
        ];
    }
}
