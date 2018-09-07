<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sql_debug_contact_name}}".
 *
 * Check the base class at app\models\base\SqlDebugContactName in order to
 * see the column names and relations.
 */
class SqlDebugContactName extends \app\models\base\SqlDebugContactName
{
    public static function primaryKey() {
        return ['name'];
    }

    public function getContacts()
    {
    	$models = \app\models\SqlContact::findAll(['name'=>$this->name]);
    	return $models;
    }

    public function getContactsIds()
    {
        return ArrayHelper::map($this->getContacts(), "id", "name");
    }
}
