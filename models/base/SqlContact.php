<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "sql_contact".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlContact.
 *
 * @property int    $id
 * @property string $name
 * @property string $document
 * @property string $sex
 * @property int    $org_id
 * @property string $org_name
 * @property string $country_id
 * @property string $community
 * @property int    $type_id
 * @property string $type_name
 * @property string $phone_personal
 */
abstract class SqlContact extends ActiveRecord
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
            [['country_id'], 'string', 'max' => 2],
            [['phone_personal'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'document' => Yii::t('app', 'Document'),
            'sex' => Yii::t('app', 'Sex'),
            'org_id' => Yii::t('app', 'Org ID'),
            'org_name' => Yii::t('app', 'Org Name'),
            'country_id' => Yii::t('app', 'Country'),
            'community' => Yii::t('app', 'Community'),
            'type_id' => Yii::t('app', 'Type ID'),
            'type_name' => Yii::t('app', 'Type Name'),
            'phone_personal' => Yii::t('app', 'Phone Personal'),
        ];
    }
}
