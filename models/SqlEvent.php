<?php

namespace app\models;

/**
 * This is the model class for table "{{%sql_event}}".
 *
 * Check the base class at app\models\base\SqlEvent in order to
 * see the column names and relations.
 */
class SqlEvent extends base\SqlEvent
{
    public static function primaryKey()
    {
        return ['id'];
    }

}
