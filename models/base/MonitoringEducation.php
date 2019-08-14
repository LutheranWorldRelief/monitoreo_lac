<?php

namespace app\models\base;

use app\components\ActiveRecord;

class MonitoringEducation extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring_education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_es', 'name_fr'], 'string', 'max' => 100],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Education Name'),
            'name_es' => Yii::t('app', 'Educacion'),
            'name_fr' => Yii::t('app', 'éducation'),
        ];
    }

    public function getContract()
    {
        return $this->hasOne(\app\models\Contact::className(), ['education_id' => 'id']);
    }


}
