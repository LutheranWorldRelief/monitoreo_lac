<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%group}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Group.
*
    * @property integer $id
    * @property string $name
    *
            * @property \app\models\ContactGroups[] $contactGroups
    */
abstract class Group extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 40]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'name' => 'Name',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getContactGroups()
    {
    return $this->hasMany(\app\models\ContactGroups::className(), ['group_id' => 'id']);
    }
}
