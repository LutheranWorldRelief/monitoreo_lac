<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sql_usuarios}}".
 *
 * Check the base class at app\models\base\SqlUsuarios in order to
 * see the column names and relations.
 */
class SqlUsuarios extends \app\models\base\SqlUsuarios {

    public static function primaryKey() {
        return ['id'];
    }

    public static function ListDataArray() {
        $models = self::find()->asArray()->orderBy(['organizacion_cafenica' => SORT_ASC, 'nombre' => SORT_ASC])->all();
//        return ArrayHelper::map($models, 'id', 'nombre', 'organizacion_cafenica');
        $result = [];
        foreach ($models as $m)
            if (isset($m['organizacion_cafenica']))
                $result[$m['organizacion_cafenica']][$m['id']] = $m['nombre'];
            else
                $result['Sin Organización'][$m['id']] = $m['nombre'];
        return $result;
    }

    public static function listDataBlank($label = 'nombre', $id = 'id') {
        return [null => 'Seleccione'] + self::ListDataArray();
    }

}
