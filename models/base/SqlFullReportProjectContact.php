<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "sql_full_report_project_contact".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlFullReportProjectContact.
 *
 * @property int    $project_id
 * @property string $project_code
 * @property string $project_name
 * @property int    $structure_id
 * @property string $structure_code
 * @property string $structure_description
 * @property int    $event_id
 * @property string $event_title
 * @property string $event_date_start
 * @property int    $event_date_start_year
 * @property int    $event_date_start_month
 * @property int    $event_date_start_day
 * @property string $event_date_end
 * @property int    $event_date_end_year
 * @property int    $event_date_end_month
 * @property int    $event_date_end_day
 * @property int    $event_country_id
 * @property string $event_country_code
 * @property string $event_country_name
 * @property int    $attendance_id
 * @property int    $contact_id
 * @property string $contact_name
 * @property string $contact_lastname
 * @property string $contact_sex
 * @property string $contact_document
 * @property string $contact_birthdate
 * @property int    $contact_education_id
 * @property string $contact_education
 * @property string $contact_phone_personal
 * @property int    $contact_men_home
 * @property int    $contact_women_home
 * @property int    $contact_organization_id
 * @property string $contact_organization
 * @property string $contact_country_code
 * @property string $contact_country
 * @property string $contact_city
 * @property string $contact_community
 * @property string $contact_municipality
 * @property string $contact_project_date_entry
 * @property string $contact_project_product
 * @property string $contact_project_area_farm
 * @property string $contact_project_dev_area
 * @property int    $contact_project_age_dev_plantation
 * @property string $contact_project_productive_area
 * @property int    $contact_project_age_prod_plantation
 * @property double $contact_project_yield
 * @property int    $organization_implementing_id
 * @property string $organization_implementing_name
 */
abstract class SqlFullReportProjectContact extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sql_full_report_project_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'structure_id', 'event_id', 'event_date_start_year', 'event_date_start_month', 'event_date_start_day', 'event_date_end_year', 'event_date_end_month', 'event_date_end_day', 'event_country_id', 'attendance_id', 'contact_id', 'contact_education_id', 'contact_men_home', 'contact_women_home', 'contact_organization_id', 'contact_project_age_dev_plantation', 'contact_project_age_prod_plantation', 'organization_implementing_id'], 'integer'],
            [['project_code', 'project_name'], 'required'],
            [['project_name', 'structure_description', 'event_title', 'event_country_name', 'contact_education', 'contact_organization', 'contact_country', 'organization_implementing_name'], 'string'],
            [['contact_birthdate', 'contact_project_date_entry'], 'safe'],
            [['contact_project_area_farm', 'contact_project_dev_area', 'contact_project_productive_area', 'contact_project_yield'], 'number'],
            [['project_code', 'contact_name', 'contact_project_product'], 'string', 'max' => 255],
            [['structure_code'], 'string', 'max' => 150],
            [['event_date_start', 'event_date_end'], 'string', 'max' => 10],
            [['event_country_code'], 'string', 'max' => 45],
            [['contact_lastname'], 'string', 'max' => 80],
            [['contact_sex'], 'string', 'max' => 1],
            [['contact_document', 'contact_city', 'contact_community', 'contact_municipality'], 'string', 'max' => 40],
            [['contact_phone_personal'], 'string', 'max' => 20],
            [['contact_country_code'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => Yii::t('app', 'Project ID'),
            'project_code' => Yii::t('app', 'Project Code'),
            'project_name' => Yii::t('app', 'Project Name'),
            'structure_id' => Yii::t('app', 'Structure ID'),
            'structure_code' => Yii::t('app', 'Structure Code'),
            'structure_description' => Yii::t('app', 'Structure Description'),
            'event_id' => Yii::t('app', 'Event ID'),
            'event_title' => Yii::t('app', 'Event Title'),
            'event_date_start' => Yii::t('app', 'Event Date Start'),
            'event_date_start_year' => Yii::t('app', 'Event Date Start Year'),
            'event_date_start_month' => Yii::t('app', 'Event Date Start Month'),
            'event_date_start_day' => Yii::t('app', 'Event Date Start Day'),
            'event_date_end' => Yii::t('app', 'Event Date End'),
            'event_date_end_year' => Yii::t('app', 'Event Date End Year'),
            'event_date_end_month' => Yii::t('app', 'Event Date End Month'),
            'event_date_end_day' => Yii::t('app', 'Event Date End Day'),
            'event_country_id' => Yii::t('app', 'Event Country ID'),
            'event_country_code' => Yii::t('app', 'Event Country Code'),
            'event_country_name' => Yii::t('app', 'Event Country Name'),
            'attendance_id' => Yii::t('app', 'Attendance ID'),
            'contact_id' => Yii::t('app', 'Contact ID'),
            'contact_name' => Yii::t('app', 'Contact Name'),
            'contact_lastname' => Yii::t('app', 'Contact Lastname'),
            'contact_sex' => Yii::t('app', 'Contact Sex'),
            'contact_document' => Yii::t('app', 'Contact Document'),
            'contact_birthdate' => Yii::t('app', 'Contact Birthdate'),
            'contact_education_id' => Yii::t('app', 'Contact Education ID'),
            'contact_education' => Yii::t('app', 'Contact Education'),
            'contact_phone_personal' => Yii::t('app', 'Contact Phone Personal'),
            'contact_men_home' => Yii::t('app', 'Contact Men Home'),
            'contact_women_home' => Yii::t('app', 'Contact Women Home'),
            'contact_organization_id' => Yii::t('app', 'Contact Organization ID'),
            'contact_organization' => Yii::t('app', 'Contact Organization'),
            'contact_country_code' => Yii::t('app', 'Contact Country Code'),
            'contact_country' => Yii::t('app', 'Contact Country'),
            'contact_city' => Yii::t('app', 'Contact City'),
            'contact_community' => Yii::t('app', 'Contact Community'),
            'contact_municipality' => Yii::t('app', 'Contact Municipality'),
            'contact_project_date_entry' => Yii::t('app', 'Contact Project Date Entry'),
            'contact_project_product' => Yii::t('app', 'Contact Project Product'),
            'contact_project_area_farm' => Yii::t('app', 'Contact Project Area Farm'),
            'contact_project_dev_area' => Yii::t('app', 'Contact Project Dev Area'),
            'contact_project_age_dev_plantation' => Yii::t('app', 'Contact Project Age Dev Plantation'),
            'contact_project_productive_area' => Yii::t('app', 'Contact Project Productive Area'),
            'contact_project_age_prod_plantation' => Yii::t('app', 'Contact Project Age Prod Plantation'),
            'contact_project_yield' => Yii::t('app', 'Contact Project Yield'),
            'organization_implementing_id' => Yii::t('app', 'Organization Implementing ID'),
            'organization_implementing_name' => Yii::t('app', 'Organization Implementing Name'),
        ];
    }
}
