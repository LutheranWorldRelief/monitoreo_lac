<?php

namespace app\models;

/**
 * This is the model class for table "{{%sql_contact}}".
 *
 * Check the base class at app\models\base\SqlContact in order to
 * see the column names and relations.
 */
class SqlContact extends base\SqlContact
{
    public $cuenta = 0;

    public static function primaryKey()
    {
        return ['id'];
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge(
            $rules,
            [
                [['cuenta'], 'safe'],
            ]
        );
    }

    public function fields()
    {
        $fields = array_merge(parent::fields(), ['cuenta']);

        return $fields;
    }
}
