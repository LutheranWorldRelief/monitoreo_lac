<?php

namespace app\models;

use app\components\UString;
use Yii;

/**
 * This is the model class for table "{{%project}}".
 *
 * Check the base class at app\models\base\Project in order to
 * see the column names and relations.
 */
class Project extends base\Project
{

    public $color1 = "#B2BB1E";
    public $color2 = "#00AAA7";
    public $color3 = "#472A2B";
    public $color4 = "#DDDF00";
    public $color5 = "#24CBE5";
    public $color6 = "#64E572";
    public $color7 = "#FF9655";
    public $color8 = "#FFF263";
    public $color9 = "#6AF9C4";
    public $colores;

    public static function CreateFromImport($data)
    {
        $codigo = $data['code'];
        if (!is_null($codigo) || !empty($codigo))
            $model = self::find()->where(['code' => $codigo])->one();
        else
            $model = new self();

        if (!$model)
            $model = new self();
        $model->attributes = $data;
        $model->establecerColores();


        $model->save();

        $estructura = Structure::find()->where(['project_id' => $model->id, 'description' => 'IMPORTACIÓN DESDE EXCEL'])->one();
        if ($estructura)
            return ['proyecto' => $model->id, 'estructura' => $estructura->id];
        $estructura = new Structure();
        $estructura->project_id = $model->id;
        $estructura->description = 'IMPORTACIÓN DESDE EXCEL';
        if ($estructura->save())
            return ['proyecto' => $model->id, 'estructura' => $estructura->id];
        return null;
    }

    public function extraFields()
    {
        $campos = parent::extraFields();
        $campos['colores'] = function () {
            return $this->colores;
        };

        return $campos;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['logo', 'file',
            'skipOnEmpty' => true,
            'uploadRequired' => 'No has seleccionado ningún archivo', //Error
            'maxSize' => 1024 * 1024 * 3, //1 MB
            'tooBig' => 'El tamaño máximo permitido es 3MB', //Error
            'minSize' => 10, //10 Bytes
            'tooSmall' => 'El tamaño mínimo permitido son 10 BYTES', //Error
            'extensions' => 'jpg,png,jpeg',
            'wrongExtension' => 'El archivo {file} no contiene una extensión permitida {extensions}', //Error
            'maxFiles' => 1,
            'tooMany' => 'El máximo de archivos permitidos es {limit}', //Error
            'checkExtensionByMimeType' => false,
        ];
        $rules[] = [['color1', 'color2', 'color3', 'color4', 'color5', 'color6', 'color7', 'color8', 'color9', 'colores'], 'safe'];
        return $rules;
    }

    public function afterFind()
    {
        $this->colores = explode(',', $this->colors);
        $this->getColores();
        return parent::afterFind();
    }

    public function getColores()
    {
        $result = ($this->colores);

        if (!$result)
            $result = [];

        $i = 1;
        foreach ($result as $c) {
            $nombre = 'color' . $i;
            $i++;
            $this->$nombre = $c;
        }
        return $result;
    }

    public function beforeValidate()
    {
        $this->establecerColores();
        return parent::beforeValidate();
    }

    public function establecerColores()
    {
        $colores = [];
        for ($i = 1; $i < 10; $i++) {
            $nombre = 'color' . $i;
            $colores[] = $this->$nombre;
        }
        $this->colors = str_replace('"', "", UString::arrayToString($colores));
    }

    public function beforeSave($insert)
    {
        $this->establecerColores();
        return parent::beforeSave($insert);
    }

    public function SubirLogo()
    {
        if ($this->logo) {
            $prefijo = UString::lowerCase(preg_replace('/\s+/', '_', $this->name));
            $nombre = $prefijo . '_logo.' . $this->logo->extension;
            $this->logo->saveAs(self::logoPath() . $nombre);
            $this->logo = $nombre;
        }
    }

    public static function logoPath()
    {
        return 'img/logo/';
    }

    public function getLogoFullPath()
    {
        return self::baseLogoPath() . $this->logo;
    }

    public static function baseLogoPath()
    {
        return Yii::$app->request->BaseUrl . '/' . self::logoPath();
    }

    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['project_id' => 'id']);
    }

    public function getnameCode()
    {
        return $this->code . ' => ' . $this->name;
    }
}
