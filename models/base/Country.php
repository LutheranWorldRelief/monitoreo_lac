<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "country".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Country.
 *
 * @property string $name_es
 * @property string $name
 * @property int $codigo_numerico
 * @property string $id
 * @property string $alfa3
 * @property string $x
 * @property string $y
 *
 * @property \app\models\Attendance[] $attendances
 * @property \app\models\Contact[] $contacts
 * @property \app\models\Organization[] $organizations
 * @property \app\models\Organization[] $organizations0
 */
abstract class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_es', 'name', 'codigo_numerico', 'id', 'alfa3', 'x', 'y'], 'required'],
            [['codigo_numerico'], 'default', 'value' => null],
            [['codigo_numerico'], 'integer'],
            [['name_es', 'name', 'x', 'y'], 'string', 'max' => 255],
            [['id'], 'string', 'max' => 2],
            [['alfa3'], 'string', 'max' => 3],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name_es' => 'Name Es',
            'name' => 'Name',
            'codigo_numerico' => 'Codigo Numerico',
            'id' => 'ID',
            'alfa3' => 'Alfa3',
            'x' => 'X',
            'y' => 'Y',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(\app\models\Attendance::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(\app\models\Contact::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizations()
    {
        return $this->hasMany(\app\models\Organization::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizations0()
    {
        return $this->hasMany(\app\models\Organization::className(), ['country_id' => 'id']);
    }
}
