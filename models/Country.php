<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "country".
 *
 * Check the base class at app\models\base\Country in order to
 * see the column names and relations.
 */
class Country extends base\Country
{

    public static function allCountries($columnNameIdiom = 'name')
    {
        $data = Country::find()
            ->select(['id', $columnNameIdiom])
            ->orderBy('id')
            ->all();

        if (count($data) > 0)
            return ArrayHelper::map($data, 'id', $columnNameIdiom);

        return [];
    }

}
