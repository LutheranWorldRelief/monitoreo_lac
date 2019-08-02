<?php


namespace app\models;


use yii\db\Query;

class MonitoringProduct extends base\MonitoringProduct
{

    public static function allProducts()
    {
        $data = MonitoringProduct::find()->orderBy('id')->all();

        if (count($data) > 0)
            return $data;

        return [];
    }

    public static function allProductNames($columNameIdiom='name')
    {
        $data = (new Query())->select([$columNameIdiom])->from('monitoring_product')->all();

        if (count($data) > 0)
            return $data;

        return [];
    }

    public static function getSpecificProduct($name){
        $datum = MonitoringProduct::find()->orwhere(['name' => $name])
            ->orWhere(['name_es' => $name])->orWhere(['name_fr' => $name])->one();
        return $datum;
    }
}