<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "sql_contact".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlContact.
 *
 * @property int $id
 * @property string $name
 * @property string $document
 * @property string $sex
 * @property int $org_id
 * @property string $org_name
 * @property string $country
 * @property string $community
 * @property int $type_id
 * @property string $type_name
 * @property string $phone_personal
 */
abstract class SqlContact extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sql_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'org_id', 'type_id'], 'integer'],
            [['org_name', 'type_name'], 'string'],
            [['name'], 'string', 'max' => 510],
            [['document', 'community'], 'string', 'max' => 40],
            [['sex'], 'string', 'max' => 1],
            [['country'], 'string', 'max' => 2],
            [['phone_personal'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'document' => 'Document',
            'sex' => 'Sex',
            'org_id' => 'Org ID',
            'org_name' => 'Org Name',
            'country' => 'Country',
            'community' => 'Community',
            'type_id' => 'Type ID',
            'type_name' => 'Type Name',
            'phone_personal' => 'Phone Personal',
        ];
    }
}
