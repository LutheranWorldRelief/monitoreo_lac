<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sql_organization}}".
 *
 * Check the base class at app\models\base\SqlOrganization in order to
 * see the column names and relations.
 */
class SqlOrganization extends \app\models\base\SqlOrganization
{
	public static function primaryKey()
	{
		return 'id';
	}
}
