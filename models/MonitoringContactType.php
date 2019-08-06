<?php


namespace app\models;

use yii\db\Query;
use yii\helpers\ArrayHelper;

class MonitoringContactType extends base\MonitoringContactType
{
    public static function allTypeContact($columnNameIdiom = 'name')
    {
        $data = MonitoringContactType::find()->select(['id', $columnNameIdiom])->orderBy('id')->all();

        if (count($data) > 0)
            return ArrayHelper::map($data, 'id', $columnNameIdiom);

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

    public static function getSpecificTypeById($id)
    {
        $datum = MonitoringContactType::findOne($id);

        return $datum;
    }
}