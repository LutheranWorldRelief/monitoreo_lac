<?php

namespace app\models;

/**
 * This is the model class for table "{{%sql_project}}".
 *
 * Check the base class at app\models\base\SqlProject in order to
 * see the column names and relations.
 */
class SqlProject extends base\SqlProject
{
    public static function primaryKey()
    {
        return ['id'];
    }
}
