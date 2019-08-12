<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "sql_event".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlEvent.
 *
 * @property int    $id
 * @property int    $structure_id
 * @property string $name
 * @property string $title
 * @property int    $implementing_organization_id
 * @property string $organizer
 * @property string $text
 * @property string $start
 * @property string $end
 * @property string $place
 * @property string $notes
 * @property int    $country_id
 * @property int    $h
 * @property int    $m
 * @property int    $t
 * @property string $country
 * @property string $organization
 * @property int    $project_id
 * @property string $project
 * @property string $code
 * @property string $structure
 */
abstract class SqlEvent extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sql_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'structure_id', 'implementing_organization_id', 'country_id', 'h', 'm', 't', 'project_id'], 'integer'],
            [['name', 'implementing_organization_id'], 'required'],
            [['title', 'text', 'notes', 'country', 'organization', 'project', 'structure'], 'string'],
            [['start', 'end'], 'safe'],
            [['name'], 'string', 'max' => 455],
            [['organizer', 'place'], 'string', 'max' => 200],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'structure_id' => Yii::t('app', 'Structure ID'),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'implementing_organization_id' => Yii::t('app', 'Implementing Organization ID'),
            'organizer' => Yii::t('app', 'Organizer'),
            'text' => Yii::t('app', 'Text'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'place' => Yii::t('app', 'Place'),
            'notes' => Yii::t('app', 'Notes'),
            'country_id' => Yii::t('app', 'Country ID'),
            'h' => Yii::t('app', 'H'),
            'm' => Yii::t('app', 'M'),
            't' => Yii::t('app', 'T'),
            'country' => Yii::t('app', 'Country'),
            'organization' => Yii::t('app', 'Organization'),
            'project_id' => Yii::t('app', 'Project ID'),
            'project' => Yii::t('app', 'Project'),
            'code' => Yii::t('app', 'Code'),
            'structure' => Yii::t('app', 'Structure'),
        ];
    }
}
