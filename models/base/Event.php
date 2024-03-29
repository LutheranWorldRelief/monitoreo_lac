<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;
use yii;

/**
 * This is the model class for table "event".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Event.
 *
 * @property int                      $id
 * @property int                      $structure_id
 * @property string                   $name
 * @property string                   $title
 * @property int                      $organization_id
 * @property string                   $organizer
 * @property string                   $text
 * @property string                   $start
 * @property string                   $end
 * @property string                   $place
 * @property string                   $notes
 * @property string                   $country_id
 *
 * @property \app\models\Attendance[] $attendances
 * @property \app\models\Country     $country
 * @property \app\models\Organization $implementingOrganization
 * @property \app\models\Structure    $structure
 */
abstract class Event extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['structure_id', 'organization_id'], 'integer'],
            [['name', 'organization_id'], 'required'],
            [['title', 'text', 'notes', 'country_id'], 'string'],
            [['start', 'end'], 'safe'],
            [['name'], 'string', 'max' => 455],
            [['organizer', 'place'], 'string', 'max' => 200],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structure::className(), 'targetAttribute' => ['structure_id' => 'id']],
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
            'organization_id' => Yii::t('app', 'Implementing Organization ID'),
            'organizer' => Yii::t('app', 'Organizer'),
            'text' => Yii::t('app', 'Text'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'place' => Yii::t('app', 'Place'),
            'notes' => Yii::t('app', 'Notes'),
            'country_id' => Yii::t('app', 'Country ID'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(\app\models\Attendance::className(), ['event_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(\app\models\DataList::className(), ['value' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getImplementingOrganization()
    {
        return $this->hasOne(\app\models\Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStructure()
    {
        return $this->hasOne(\app\models\Structure::className(), ['id' => 'structure_id']);
    }
}
