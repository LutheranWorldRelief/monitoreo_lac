<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%email}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Email.
*
    * @property integer $id
    * @property string $email
    * @property string $type
    * @property string $public_visible
    * @property string $contact_visible
    * @property integer $contact_id
    *
            * @property \app\models\Contact $contact
    */
abstract class Email extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['contact_id'], 'required'],
            [['contact_id'], 'integer'],
            [['email'], 'string', 'max' => 254],
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
    'email' => 'Email',
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
