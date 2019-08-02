<?php


namespace app\models;

use app\components\UCatalogo;
use bedezign\yii2\audit\components\panels\RendersSummaryChartTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Query;

class MonitoringProduct extends base\MonitoringProduct
{

    public static function allProduct()
    {
        $data = MonitoringProduct::find()->orderBy('id')->all();

        if (count($data) > 0)
            return $data;

        return [];

    }
}