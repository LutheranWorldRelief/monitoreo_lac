<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%organization}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Organization.
*
    * @property integer $id
    * @property string $name
    * @property string $country
    * @property integer $organization_type_id
    * @property integer $organization_id
    * @property string $description
    * @property integer $country_id
    * @property integer $is_implementer
    *
            * @property \app\models\Contact[] $contacts
            * @property \app\models\Event[] $events
            * @property \app\models\DataList $country0
            * @property \app\models\Organization $organization
            * @property \app\models\Organization[] $organizations
            * @property \app\models\OrganizationType $organizationType
    */
abstract class Organization extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['organization_type_id', 'organization_id', 'country_id', 'is_implementer'], 'integer'],
            [['country'], 'string', 'max' => 2],
            [['description'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['organization_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationType::className(), 'targetAttribute' => ['organization_type_id' => 'id']]
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
    'country' => 'Country',
    'organization_type_id' => 'Organization Type ID',
    'organization_id' => 'Organization ID',
    'description' => 'Description',
    'country_id' => 'Country ID',
    'is_implementer' => 'Is Implementer',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getContacts()
    {
    return $this->hasMany(\app\models\Contact::className(), ['organization_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEvents()
    {
    return $this->hasMany(\app\models\Event::className(), ['implementing_organization_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCountry0()
    {
    return $this->hasOne(\app\models\DataList::className(), ['id' => 'country_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrganization()
    {
    return $this->hasOne(\app\models\Organization::className(), ['id' => 'organization_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrganizations()
    {
    return $this->hasMany(\app\models\Organization::className(), ['organization_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrganizationType()
    {
    return $this->hasOne(\app\models\OrganizationType::className(), ['id' => 'organization_type_id']);
    }
}
