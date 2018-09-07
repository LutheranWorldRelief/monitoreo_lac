<?php

namespace app\models\base;

use Yii;

/**
* This is the model class for table "{{%attendeetype}}".
* Please do not add custom code to this file, as it is supposed to be overriden
* by the gii model generator. Custom code belongs to app\models\Attendeetype.
*
    * @property integer $id
    * @property string $name
    *
            * @property \app\models\Attendance[] $attendances
    */
abstract class Attendeetype extends \app\components\ActiveRecord
{
/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50]
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
    public function getAttendances()
    {
    return $this->hasMany(\app\models\Attendance::className(), ['type_id' => 'id']);
    }
}
