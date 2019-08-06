<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "organization".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Organization.
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $country
 * @property int                          $organization_type_id
 * @property int                          $organization_id
 * @property string                       $description
 * @property string                       $country_id
 * @property int                          $is_implementer
 *
 * @property \app\models\Contact[]        $contacts
 * @property \app\models\Event[]          $events
 * @property \app\models\Country         $country0
 * @property \app\models\OrganizationType $organizationType
 * @property \app\models\Organization     $organization
 * @property \app\models\Organization[]   $organizations
 */
abstract class Organization extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['organization_type_id', 'organization_id', 'country_number', 'is_implementer'], 'integer'],
            [['country_id'], 'string', 'max' => 2],
            [['description'], 'string', 'max' => 255],
           [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['organization_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationType::className(), 'targetAttribute' => ['organization_type_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
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
            'country_number' => 'Country Number',
            'organization_type_id' => 'Organization Type ID',
            'organization_id' => 'Organization ID',
            'description' => 'Description',
            'country_id' => 'Country ID',
            'is_implementer' => 'Is Implementer',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(\app\models\Contact::className(), ['organization_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(\app\models\Event::className(), ['organization_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry0()
    {
        return $this->hasOne(\app\models\DataList::className(), ['id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrganizationType()
    {
        return $this->hasOne(\app\models\OrganizationType::className(), ['id' => 'organization_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(\app\models\Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrganizations()
    {
        return $this->hasMany(\app\models\Organization::className(), ['organization_id' => 'id']);
    }
}
