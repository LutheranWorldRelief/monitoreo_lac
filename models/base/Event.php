<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "event".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Event.
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
 *
 * @property \app\models\Attendance[] $attendances
 * @property \app\models\DataList $country
 * @property \app\models\Organization $implementingOrganization
 * @property \app\models\Structure $structure
 */
abstract class Event extends \app\components\ActiveRecord
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
            [['structure_id', 'implementing_organization_id', 'country_id'], 'integer'],
            [['name', 'implementing_organization_id'], 'required'],
            [['title', 'text', 'notes'], 'string'],
            [['start', 'end'], 'safe'],
            [['name'], 'string', 'max' => 455],
            [['organizer', 'place'], 'string', 'max' => 200],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['implementing_organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['implementing_organization_id' => 'id']],
            [['structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structure::className(), 'targetAttribute' => ['structure_id' => 'id']],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(\app\models\Attendance::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(\app\models\DataList::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImplementingOrganization()
    {
        return $this->hasOne(\app\models\Organization::className(), ['id' => 'implementing_organization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructure()
    {
        return $this->hasOne(\app\models\Structure::className(), ['id' => 'structure_id']);
    }
}
