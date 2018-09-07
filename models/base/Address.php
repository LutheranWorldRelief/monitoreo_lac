<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%address}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Address.
*
    * @property integer $id
    * @property string $street
    * @property string $city
    * @property string $state
    * @property string $country
    * @property string $zip
    * @property string $type
    * @property string $public_visible
    * @property string $contact_visible
    * @property integer $contact_id
    *
            * @property \app\models\Contact $contact
    */
abstract class Address extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['city', 'state', 'country', 'type', 'public_visible', 'contact_visible', 'contact_id'], 'required'],
            [['contact_id'], 'integer'],
            [['street'], 'string', 'max' => 50],
            [['city', 'state'], 'string', 'max' => 40],
            [['country'], 'string', 'max' => 2],
            [['zip'], 'string', 'max' => 10],
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
    'street' => 'Street',
    'city' => 'City',
    'state' => 'State',
    'country' => 'Country',
    'zip' => 'Zip',
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
