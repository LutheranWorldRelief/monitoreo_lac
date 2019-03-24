<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "sql_event".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlEvent.
 *
 * @property int $id
 * @property int $structure_id
 * @property string $name
 * @property string $title
 * @property int $implementing_organization_id
 * @property string $organizer
 * @property string $text
 * @property string $start
 * @property string $end
 * @property string $place
 * @property string $notes
 * @property int $country_id
 * @property int $h
 * @property int $m
 * @property int $t
 * @property string $country
 * @property string $organization
 * @property int $project_id
 * @property string $project
 * @property string $code
 * @property string $structure
 */
abstract class SqlEvent extends \app\components\ActiveRecord
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
            'id' => 'ID',
            'structure_id' => 'Structure ID',
            'name' => 'Name',
            'title' => 'Title',
            'implementing_organization_id' => 'Implementing Organization ID',
            'organizer' => 'Organizer',
            'text' => 'Text',
            'start' => 'Start',
            'end' => 'End',
            'place' => 'Place',
            'notes' => 'Notes',
            'country_id' => 'Country ID',
            'h' => 'H',
            'm' => 'M',
            't' => 'T',
            'country' => 'Country',
            'organization' => 'Organization',
            'project_id' => 'Project ID',
            'project' => 'Project',
            'code' => 'Code',
            'structure' => 'Structure',
        ];
    }
}
