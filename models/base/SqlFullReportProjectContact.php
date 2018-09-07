<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%sql_full_report_project_contact}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\SqlFullReportProjectContact.
*
    * @property integer $project_id
    * @property string $project_code
    * @property string $project_name
    * @property integer $structure_id
    * @property string $structure_code
    * @property string $structure_description
    * @property integer $event_id
    * @property string $event_title
    * @property string $event_date_start
    * @property integer $event_date_start_year
    * @property integer $event_date_start_month
    * @property integer $event_date_start_day
    * @property string $event_date_end
    * @property integer $event_date_end_year
    * @property integer $event_date_end_month
    * @property integer $event_date_end_day
    * @property integer $attendance_id
    * @property integer $contact_id
    * @property string $contact_name
    * @property string $contact_lastname
    * @property string $contact_sex
    * @property string $contact_document
    * @property string $contact_birthdate
    * @property integer $contact_education_id
    * @property string $contact_education
    * @property string $contact_phone_personal
    * @property integer $contact_men_home
    * @property integer $contact_women_home
    * @property integer $contact_organization_id
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
    * @property integer $contact_project_age_dev_plantation
    * @property string $contact_project_productive_area
    * @property integer $contact_project_age_prod_plantation
    * @property double $contact_project_yield
    * @property integer $organization_implementing_id
    * @property string $organization_implementing_name
*/
abstract class SqlFullReportProjectContact extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['project_id', 'structure_id', 'event_id', 'event_date_start_year', 'event_date_start_month', 'event_date_start_day', 'event_date_end_year', 'event_date_end_month', 'event_date_end_day', 'attendance_id', 'contact_id', 'contact_education_id', 'contact_men_home', 'contact_women_home', 'contact_organization_id', 'contact_project_age_dev_plantation', 'contact_project_age_prod_plantation', 'organization_implementing_id'], 'integer'],
            [['project_code', 'project_name'], 'required'],
            [['project_name', 'structure_description', 'event_title', 'contact_education', 'contact_country'], 'string'],
            [['event_date_start', 'event_date_end', 'contact_birthdate', 'contact_project_date_entry'], 'safe'],
            [['contact_project_area_farm', 'contact_project_dev_area', 'contact_project_productive_area', 'contact_project_yield'], 'number'],
            [['project_code', 'contact_name', 'contact_project_product'], 'string', 'max' => 255],
            [['structure_code'], 'string', 'max' => 150],
            [['contact_lastname'], 'string', 'max' => 80],
            [['contact_sex'], 'string', 'max' => 1],
            [['contact_document', 'contact_city', 'contact_community', 'contact_municipality'], 'string', 'max' => 40],
            [['contact_phone_personal'], 'string', 'max' => 20],
            [['contact_organization', 'organization_implementing_name'], 'string', 'max' => 50],
            [['contact_country_code'], 'string', 'max' => 2]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'project_id' => 'Project ID',
    'project_code' => 'Project Code',
    'project_name' => 'Project Name',
    'structure_id' => 'Structure ID',
    'structure_code' => 'Structure Code',
    'structure_description' => 'Structure Description',
    'event_id' => 'Event ID',
    'event_title' => 'Event Title',
    'event_date_start' => 'Event Date Start',
    'event_date_start_year' => 'Event Date Start Year',
    'event_date_start_month' => 'Event Date Start Month',
    'event_date_start_day' => 'Event Date Start Day',
    'event_date_end' => 'Event Date End',
    'event_date_end_year' => 'Event Date End Year',
    'event_date_end_month' => 'Event Date End Month',
    'event_date_end_day' => 'Event Date End Day',
    'attendance_id' => 'Attendance ID',
    'contact_id' => 'Contact ID',
    'contact_name' => 'Contact Name',
    'contact_lastname' => 'Contact Lastname',
    'contact_sex' => 'Contact Sex',
    'contact_document' => 'Contact Document',
    'contact_birthdate' => 'Contact Birthdate',
    'contact_education_id' => 'Contact Education ID',
    'contact_education' => 'Contact Education',
    'contact_phone_personal' => 'Contact Phone Personal',
    'contact_men_home' => 'Contact Men Home',
    'contact_women_home' => 'Contact Women Home',
    'contact_organization_id' => 'Contact Organization ID',
    'contact_organization' => 'Contact Organization',
    'contact_country_code' => 'Contact Country Code',
    'contact_country' => 'Contact Country',
    'contact_city' => 'Contact City',
    'contact_community' => 'Contact Community',
    'contact_municipality' => 'Contact Municipality',
    'contact_project_date_entry' => 'Contact Project Date Entry',
    'contact_project_product' => 'Contact Project Product',
    'contact_project_area_farm' => 'Contact Project Area Farm',
    'contact_project_dev_area' => 'Contact Project Dev Area',
    'contact_project_age_dev_plantation' => 'Contact Project Age Dev Plantation',
    'contact_project_productive_area' => 'Contact Project Productive Area',
    'contact_project_age_prod_plantation' => 'Contact Project Age Prod Plantation',
    'contact_project_yield' => 'Contact Project Yield',
    'organization_implementing_id' => 'Organization Implementing ID',
    'organization_implementing_name' => 'Organization Implementing Name',
];
}
}
