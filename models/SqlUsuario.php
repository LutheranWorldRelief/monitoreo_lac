<?php

namespace app\models;

/**
 * This is the model class for table "sql_usuario".
 *
 * Check the base class at app\models\base\SqlUsuario in order to
 * see the column names and relations.
 */
class SqlUsuario extends base\SqlUsuario
{
    public static function primaryKey()
    {
        return ['id'];
    }
}
