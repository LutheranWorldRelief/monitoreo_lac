<?php

namespace app\components;

use yii\helpers\ArrayHelper;

class ActiveRecord extends \yii\db\ActiveRecord
{

    // const NG_FILTROS_CAMPOS_EQUIVALENTES = [];

    public function behaviors()
    {
        return [
            [
                'class' => 'app\nestic\behaviors\RelacionesMaestroDetalleBehavior',
            ],
            'bedezign\yii2\audit\AuditTrailBehavior',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields = array_merge(
            $fields,
            [
                'errors',
            ]
        );

        return $fields;
    }

    public static function ListDataArray()
    {
        return self::find()->asArray()->all();
    }

    public static function ListDataJson()
    {
        return \yii\helpers\Json::encode(self::ListDataArray());
    }

    public static function ListDataJsonBlank()
    {
        return \yii\helpers\Json::encode([null => 'Seleccione...'] + self::ListDataArray());
    }

    public static function listData($label = 'nombre', $id = 'id')
    {
        return ArrayHelper::map(self::ListDataArray(), $id, $label);
    }

    public static function listDataModel($label = 'nombre', $id = 'id'){
        return ArrayHelper::map(self::find()->all(), $id, $label);
    }

    public static function listDataBlank($label = 'nombre', $id = 'id')
    {
        return [null => 'Seleccione'] + self::listData($label, $id);
    }

    protected function stringToArray($value, $glue = ',')
    {
        if (!$value || empty($value) || !is_string($value))
            return [];
        return explode($glue, $value);
    }

    protected function arrayToString($value, $glue = ',')
    {
        if (!$value || !is_array($value) || (count($value) <= 0))
            return '';
        return implode($glue, $value);
    }

    protected function addValueToArray($array, $value, $id = null)
    {
        if (!is_array($array))
            $array = [];

        if ($id)
            $array[$id] = $value;
        else
            $array[] = $value;

        return $array;
    }

    public function createJsonFile($path, $dataRaw)
    {
        $fp = fopen($path, 'w');
        fwrite($fp, json_encode($dataRaw));
        fclose($fp);
    }

    public function readJsonFile($path, $default = '')
    {
        if (file_exists($path))
            return file_get_contents($path);
        return $default;
    }

    public static function modelsToArray(array $extra = [], array $with = [], array $campos = [], array $order = [])
    {
        $array = [];
        foreach (self::find()->orderBy($order)->with($with)->all() as $m) {
            $model = $m->toArray($campos, $extra);
            foreach ($model as $key => $value) {
                if (is_int($value))
                    $model[$key] = (string)$value;
            }
            $array[] = $model;
        }
        return $array;
    }

    public function ToArrayString(array $extra = [], array $campos = [])
    {
        $model = $this->toArray($campos, $extra);
        foreach ($model as $key => $value) {
            if (is_int($value))
                $model[$key] = (string)$value;
        }
        return $model;
    }

    public static function getCamposExtra($class)
    {
        $attr = new $class;
        $atributos = [];
        foreach ($attr->getAttributes() as $key => $value)
            $atributos[] = $key;
        $atributos['_errors'] = function ($model) {
            return $model->getFirstErrors();
        };
        return $atributos;
    }

    public function toArrayCamposExtra()
    {
        $clase = $this->className();
        return \yii\helpers\ArrayHelper::toArray($this, [
            $clase => $clase::getCamposExtra($clase)
        ]);
    }

    public static function ngFiltros($filtros, $orden, $class)
    {

        $ng_filtros_campos_equivalentes = [];

        $model = new $class;
        $atributos = $model->attributes;

        $query = self::find();
        foreach ($ng_filtros_campos_equivalentes as $value) {
            $campo = ArrayHelper::getValue($filtros, $value);
            if (ArrayHelper::keyExists($value, $atributos)) {
                if (!empty($campo)) {
                    $query->andWhere([$value => $campo]);
                    unset($filtros[$value]);
                }
            } else
                unset($filtros[$value]);
        }
        foreach ($filtros as $key => $value)
            if (ArrayHelper::keyExists($key, $atributos))
                $query->andWhere("$key like '%$value%'");

        if (!empty($orden))
            $query->orderBy($orden);
        return $query;
    }

    public function sendMail($subject, $body, $recipients)
    {

        $isEmail = Yii::$app->params['isEmail'];
        if ($isEmail) {
            $subject = $subject . " (LWR)";
            $message = "<p style='margin-bottom: 20px;'>Estimado(s), </p>";
            $message .= "<p style='font-size: 16px;'>" . $body;
            $message .= "<p style='margin-top: 40px;'>Muchas gracias por tu atención.</p>";
            $mail = new \app\components\Mail;
            $mail->enviarCorreo($subject, $message, $recipients);
        }
    }

}
