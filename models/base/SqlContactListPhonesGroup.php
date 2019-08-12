<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "sql_contact_list_phones_group".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlContactListPhonesGroup.
 *
 * @property int    $contact_id
 * @property int    $cuenta
 * @property string $phone_personal
 */
abstract class SqlContactListPhonesGroup extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sql_contact_list_phones_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'cuenta'], 'integer'],
            [['phone_personal'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => Yii::t('app', 'Contact ID'),
            'cuenta' => Yii::t('app', 'Cuenta'),
            'phone_personal' => Yii::t('app', 'Phone Personal'),
        ];
    }
}
