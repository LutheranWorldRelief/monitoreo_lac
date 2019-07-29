<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "contact".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Contact.
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $last_name
 * @property string                       $first_name
 * @property string                       $document
 * @property string                       $title
 * @property int                          $organization_id
 * @property string                       $sex
 * @property string                       $community
 * @property string                       $municipality
 * @property string                       $city
 * @property string                       $country_id
 * @property int                          $education_id
 * @property string                       $phone_personal
 * @property string                       $phone_work
 * @property int                          $men_home
 * @property int                          $women_home
 * @property string                       $created
 * @property string                       $modified
 * @property int                          $type_id
 * @property string                       $birthdate
 *
 * @property \app\models\Attendance[]     $attendances
 * @property \app\models\DataList         $education
 * @property \app\models\Organization     $organization
 * @property \app\models\DataList         $type
 * @property \app\models\ProjectContact[] $projectContacts
 */
abstract class Contact extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

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
            [['country_id'], 'string', 'max' => 2],
            [['phone_personal', 'phone_work'], 'string', 'max' => 20],
            [['education_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['education_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['type_id' => 'id']],
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
            'country_id' => 'Country',
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
     * @return ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(\app\models\Attendance::className(), ['contact_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(\app\models\DataList::className(), ['id' => 'education_id']);
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
    public function getType()
    {
        return $this->hasOne(\app\models\DataList::className(), ['id' => 'type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProjectContacts()
    {
        return $this->hasMany(\app\models\ProjectContact::className(), ['contact_id' => 'id']);
    }
}
