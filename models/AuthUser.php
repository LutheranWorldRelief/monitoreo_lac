<?php

namespace app\models;

use app\components\UPasswords;
use app\components\UToken;
use app\models\base\AuthUser as BaseUser;
use mdm\admin\components\Helper;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%auth_user}}".
 *
 * Check the base class at app\models\base\AuthUser in order to
 * see the column names and relations.
 */
class AuthUser extends BaseUser implements IdentityInterface
{

    const ESTADO_ACTIVO = 1;
    const ESTADO_INACTIVO = 0;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
        //        return static::findOne(['id' => $id, 'is_active' => self::ESTADO_ACTIVO]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $model = static::findOne(['access_token' => $token]);
        if ($model && UToken::ValidaToken($token))
            return $model;
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'is_active' => self::ESTADO_ACTIVO]);
    }

    /**
     * @param string $id user_id from audit_entry table
     *
     * @return mixed|string
     */
    public static function userIdentifierCallback($id)
    {
        $user = self::findOne($id);
        return $user ? Html::a($user->getNombre(), ['/user/admin/update', 'id' => $user->id]) : $id;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Contraseña',
            'last_login' => 'Último inicio de sesión',
            'is_superuser' => 'Es superusuario',
            'username' => 'Usuario',
            'first_name' => 'Nombres',
            'last_name' => 'Apellidos',
            'email' => 'Correo Electrónico',
            'is_active' => 'Activo',
            'cedula' => 'Cédula',
            'sexo' => 'Sexo',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return UPasswords::validatePassword($password, $this->password);
    }

    public function generarToken($datos = [])
    {
        $this->access_token = UToken::GenerarToken($this->username, $this->getDatosBasicos() + $datos);
        $this->save();
    }

    private function getDatosBasicos()
    {
        return ['nombre' => $this->getNombre(), 'cedula' => $this->cedula, 'sexo' => $this->sexo];
    }

    public function getNombre()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function create()
    {
        $this->is_active = 1;
        $this->last_login = null;
        $this->is_superuser = 1;
        $this->is_staff = 1;
        $this->date_joined = date('Y-m-d h:m:i');
        $this->hashClave();
        return $this->save();
    }

    private function hashClave()
    {
        $salt = UPasswords::generateSalt();
        $hash = UPasswords::encriptar($this->password, $salt);
        return $this->password = UPasswords::passwordString($hash, $salt);
    }

    public function modificar($pass)
    {
        if ($this->password === 'nestic')
            $this->password = $pass;
        else
            $this->hashClave();

        return $this->save(true);
    }

    public function tienePermiso($ruta)
    {
        if (Helper::checkRoute('/' . $ruta))
            return true;
        return false;
    }

    public function getactivo()
    {
        return $this->is_active ? 'Si' : 'No';
    }

    public function getIsSuperUser()
    {
        return $this->is_superuser ? true : false;
    }

    public function getsuperUsuario()
    {
        return $this->is_superuser ? 'Si' : 'No';
    }

    public function getContactAllowed()
    {
        $proyectos = $this->projects;
        if (!is_array($proyectos))
            $proyectos = [];
        $paises = $this->countries;
        if (!is_array($paises))
            $paises = [];

        $query = (new Query());
        $query->select([
            "value",
        ])->from('data_list')
            ->where(['in', 'id', $paises]);
        $paisesCode = $query->column();

        $queryContact = (new Query());
        $queryContact->select('contact_id')
            ->from('sql_full_report_project_contact')
            ->orWhere(['in', 'contact_country_code', $paisesCode])
            ->orWhere(['in', 'project_id', $proyectos])
            ->andWhere('contact_id is not null')
            ->groupBy('contact_id');
        return $queryContact->column();
    }

    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ ALLOWED COUNTRY

    public function getEventAllowed()
    {
        $proyectos = $this->projects;
        if (!is_array($proyectos))
            $proyectos = [];
        $paises = $this->countries;
        if (!is_array($paises))
            $paises = [];


        $query = (new Query());
        $query->select('id')
            ->from('sql_event')
            ->orWhere(['in', 'country_id', $paises])
            ->orWhere(['in', 'project_id', $proyectos])
            ->groupBy('id');
        return $query->column();
    }

    public function countryAllow($country)
    {
        return $this->countryCodeAllow($country->value);
    }

    public function countryCodeAllow($countryCode)
    {
        if ($this->is_superuser)
            return true;
        return array_key_exists($countryCode, $this->countriesList());
    }

    public function countriesList()
    {
        return ArrayHelper::map($this->countriesModels(), "id", "name");
    }

    public function countriesModels()
    {
        if ($this->is_superuser) {
            $parent = Country::find()->orderBy('name')->all();
            if (count($parent) > 0)
                return $parent;
        } else {

            return Country::find()
                ->where(['in','id',$this->countriesArray()])
                ->orderBy('name')
                ->all();
        }

        return [];
    }

    public function countriesArray()
    {
        if (!is_array($this->countries))
            return [];
        return $this->countries;
    }

    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ ALLOWED PROJECTS

    public function countryIdAllow($countryId)
    {
        if ($this->is_superuser)
            return true;
        $array = ArrayHelper::map($this->countriesModels(), 'id', 'name');
        return array_key_exists($countryId, $array);
    }

    public function projectAllow($project)
    {
        return $this->projectIdAllow($project->id);
    }

    public function projectIdAllow($projectId)
    {
        if ($this->is_superuser)
            return true;
        return array_key_exists($projectId, $this->projectsList());
    }

    public function projectsList()
    {
        return ArrayHelper::map($this->projectsModels(), 'id', 'name');
    }

    public function projectsModels()
    {
        if ($this->is_superuser) {
            return Project::find()
                ->orderBy('name')
                ->all();
        } else {
            return Project::find()
                ->where(['id' => $this->projectsArray()])
                ->orderBy('name')
                ->all();
        }

        return [];
    }

    public function projectsArray()
    {
        if (!is_array($this->projects))
            return [];
        return $this->projects;
    }

}
