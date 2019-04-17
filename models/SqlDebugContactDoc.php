<?php

namespace app\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sql_debug_contact_doc}}".
 *
 * Check the base class at app\models\base\SqlDebugContactDoc in order to
 * see the column names and relations.
 */
class SqlDebugContactDoc extends base\SqlDebugContactDoc
{
    public static function primaryKey()
    {
        return ['doc_id'];
    }

    public function getContactsIds()
    {
        return ArrayHelper::map($this->getContacts(), "id", "name");
    }

    public function getContacts()
    {
        $query = SqlContact::find();
        $query->andWhere('trim(replace(replace(document,"-",""), " ", "")) = "' . $this->doc_id . '"');
        $models = $query->all();
        return $models;
    }
}
