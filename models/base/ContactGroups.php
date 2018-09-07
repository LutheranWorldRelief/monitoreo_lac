<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%contact_groups}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\ContactGroups.
*
    * @property integer $id
    * @property integer $contact_id
    * @property integer $group_id
    *
            * @property \app\models\Contact $contact
            * @property \app\models\Group $group
    */
abstract class ContactGroups extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['contact_id', 'group_id'], 'required'],
            [['contact_id', 'group_id'], 'integer'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'contact_id' => 'Contact ID',
    'group_id' => 'Group ID',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getContact()
    {
    return $this->hasOne(\app\models\Contact::className(), ['id' => 'contact_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getGroup()
    {
    return $this->hasOne(\app\models\Group::className(), ['id' => 'group_id']);
    }
}
