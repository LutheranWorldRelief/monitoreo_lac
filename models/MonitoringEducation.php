<?php


namespace app\models;

use yii\db\Query;
use yii\helpers\ArrayHelper;

class MonitoringEducation extends base\MonitoringEducation
{
    public static function allEducations($columnNameIdiom = 'name')
    {
        $data = MonitoringEducation::find()->select(['id', $columnNameIdiom])->orderBy('id')->all();

        if (count($data) > 0)
            return ArrayHelper::map($data, 'id', $columnNameIdiom);

        return [];

    }

    public static function allEducationNames($columnNameIdiom = 'name')
    {
        $data = (new Query())->select([$columnNameIdiom])->from('monitoring_education')->all();

        if (count($data) > 0)
            return $data;

        return [];
    }


    public static function getSpecificEducation($name)
    {
        $datum = MonitoringEducation::find()->orwhere(['name' => $name])
            ->orWhere(['name_es' => $name])->orWhere(['name_fr' => $name])->one();
        return $datum;
    }
}