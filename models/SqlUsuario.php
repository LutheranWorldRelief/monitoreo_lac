<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sql_usuario".
 *
 * Check the base class at app\models\base\SqlUsuario in order to
 * see the column names and relations.
 */
class SqlUsuario extends \app\models\base\SqlUsuario
{
    public static function primaryKey()
    {
        return ['id'];
    }
}
