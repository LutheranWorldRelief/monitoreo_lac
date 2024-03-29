<?php

namespace app\models;

/**
 * This is the model class for table "{{%sql_contact_event}}".
 *
 * Check the base class at app\models\base\SqlContactEvent in order to
 * see the column names and relations.
 */
class SqlContactEvent extends base\SqlContactEvent
{
    public static function primaryKey()
    {
        return ['contact_id'];
    }
}
