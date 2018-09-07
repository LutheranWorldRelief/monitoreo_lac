<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sql_contact_list_phones_group}}".
 *
 * Check the base class at app\models\base\SqlContactListPhonesGroup in order to
 * see the column names and relations.
 */
class SqlContactListPhonesGroup extends \app\models\base\SqlContactListPhonesGroup
{

    public static function primaryKey() {
        return ['contact_id'];
    }
}
