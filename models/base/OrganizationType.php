<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "organization_type".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\OrganizationType.
 *
 * @property int $id
 * @property string $abbreviation
 * @property string $name
 * @property string $description
 *
 * @property \app\models\Organization[] $organizations
 */
abstract class OrganizationType extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['abbreviation', 'name'], 'required'],
            [['description'], 'string'],
            [['abbreviation'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'abbreviation' => 'Abbreviation',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizations()
    {
        return $this->hasMany(\app\models\Organization::className(), ['organization_type_id' => 'id']);
    }
}
