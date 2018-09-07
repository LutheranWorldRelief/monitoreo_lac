<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%socialnetwork}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Socialnetwork.
*
    * @property integer $id
    * @property string $handle
    * @property string $type
    * @property string $public_visible
    * @property string $contact_visible
    * @property integer $contact_id
    *
            * @property \app\models\Contact $contact
    */
abstract class Socialnetwork extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['handle', 'type', 'public_visible', 'contact_visible', 'contact_id'], 'required'],
            [['contact_id'], 'integer'],
            [['handle'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 20],
            [['public_visible', 'contact_visible'], 'string', 'max' => 5],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'handle' => 'Handle',
    'type' => 'Type',
    'public_visible' => 'Public Visible',
    'contact_visible' => 'Contact Visible',
    'contact_id' => 'Contact ID',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getContact()
    {
    return $this->hasOne(\app\models\Contact::className(), ['id' => 'contact_id']);
    }
}
