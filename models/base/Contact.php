<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%contact}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Contact.
*
    * @property integer $id
    * @property string $name
    * @property string $last_name
    * @property string $first_name
    * @property string $document
    * @property string $title
    * @property integer $organization_id
    * @property string $sex
    * @property string $community
    * @property string $municipality
    * @property string $city
    * @property string $country
    * @property integer $education_id
    * @property string $phone_personal
    * @property string $phone_work
    * @property integer $men_home
    * @property integer $women_home
    * @property string $created
    * @property string $modified
    * @property integer $type_id
    * @property string $birthdate
    *
            * @property \app\models\Address[] $addresses
            * @property \app\models\Attendance[] $attendances
            * @property \app\models\DataList $type
            * @property \app\models\DataList $education
            * @property \app\models\Organization $organization
            * @property \app\models\ContactGroups[] $contactGroups
            * @property \app\models\Email[] $emails
            * @property \app\models\Phonenumber[] $phonenumbers
            * @property \app\models\ProjectContact[] $projectContacts
            * @property \app\models\Socialnetwork[] $socialnetworks
    */
abstract class Contact extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['organization_id', 'education_id', 'men_home', 'women_home', 'type_id'], 'integer'],
            [['created', 'modified', 'birthdate'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['last_name', 'first_name'], 'string', 'max' => 80],
            [['document', 'community', 'municipality', 'city'], 'string', 'max' => 40],
            [['title'], 'string', 'max' => 100],
            [['sex'], 'string', 'max' => 1],
            [['country'], 'string', 'max' => 2],
            [['phone_personal', 'phone_work'], 'string', 'max' => 20],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['education_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['education_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']]
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
    'last_name' => 'Last Name',
    'first_name' => 'First Name',
    'document' => 'Document',
    'title' => 'Title',
    'organization_id' => 'Organization ID',
    'sex' => 'Sex',
    'community' => 'Community',
    'municipality' => 'Municipality',
    'city' => 'City',
    'country' => 'Country',
    'education_id' => 'Education ID',
    'phone_personal' => 'Phone Personal',
    'phone_work' => 'Phone Work',
    'men_home' => 'Men Home',
    'women_home' => 'Women Home',
    'created' => 'Created',
    'modified' => 'Modified',
    'type_id' => 'Type ID',
    'birthdate' => 'Birthdate',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAddresses()
    {
    return $this->hasMany(\app\models\Address::className(), ['contact_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAttendances()
    {
    return $this->hasMany(\app\models\Attendance::className(), ['contact_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getType()
    {
    return $this->hasOne(\app\models\DataList::className(), ['id' => 'type_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEducation()
    {
    return $this->hasOne(\app\models\DataList::className(), ['id' => 'education_id']);
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
    public function getContactGroups()
    {
    return $this->hasMany(\app\models\ContactGroups::className(), ['contact_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEmails()
    {
    return $this->hasMany(\app\models\Email::className(), ['contact_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPhonenumbers()
    {
    return $this->hasMany(\app\models\Phonenumber::className(), ['contact_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProjectContacts()
    {
    return $this->hasMany(\app\models\ProjectContact::className(), ['contact_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSocialnetworks()
    {
    return $this->hasMany(\app\models\Socialnetwork::className(), ['contact_id' => 'id']);
    }
}
