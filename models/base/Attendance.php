<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "attendance".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Attendance.
 *
 * @property int                 $id
 * @property int                 $event_id
 * @property int                 $contact_id
 * @property string              $document
 * @property string              $sex
 * @property string              $country_id
 * @property string              $community
 * @property int                 $organization_id
 * @property string              $phone_personal
 * @property int                 $type_id
 *
 * @property \app\models\Contact $contact
 * @property \app\models\Event   $event
 */
abstract class Attendance extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'contact_id', 'organization_id', 'type_id'], 'integer'],
            [['document', 'country_id', 'phone_personal'], 'string', 'max' => 45],
            [['sex'], 'string', 'max' => 1],
            [['community'], 'string', 'max' => 255],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'contact_id' => 'Contact ID',
            'document' => 'Document',
            'sex' => 'Sex',
            'country_id' => 'Country',
            'community' => 'Community',
            'organization_id' => 'Org ID',
            'phone_personal' => 'Phone Personal',
            'type_id' => 'Type ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(\app\models\Contact::className(), ['id' => 'contact_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\app\models\Event::className(), ['id' => 'event_id']);
    }
}
