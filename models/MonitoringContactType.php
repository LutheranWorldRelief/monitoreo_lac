<?php


namespace app\models;

use yii\db\Query;

class MonitoringContactType extends base\MonitoringContactType
{
    public static function allTypeContact($columnNameIdiom = 'name')
    {
        $data = MonitoringContactType::find()->select(['id', $columnNameIdiom])->orderBy('id')->all();

        if (count($data) > 0)
            return $data;

        return [];

    }

    public static function allTypeNames($columnNameIdiom = 'name')
    {
        $data = (new Query())->select([$columnNameIdiom])->from('monitoring_contacttype')->all();

        if (count($data) > 0)
            return $data;

        return [];
    }


    public static function getSpecificType($name)
    {
        $datum = MonitoringContactType::find()->orwhere(['name' => $name])
            ->orWhere(['name_es' => $name])->orWhere(['name_fr' => $name])->one();
        return $datum;
    }
}