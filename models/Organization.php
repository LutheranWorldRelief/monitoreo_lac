<?php

namespace app\models;

use app\components\UCatalogo;

/**
 * This is the model class for table "{{%organization}}".
 *
 * Check the base class at app\models\base\Organization in order to
 * see the column names and relations.
 */
class Organization extends base\Organization
{
    public static function getIdFromName($name)
    {
        $model = self::find()->where(['name' => $name])->one();
        if ($model)
            return $model->id;
        return null;
    }

    public function getImplementer()
    {
        return $this->is_implementer ? 'Si' : 'No';
    }

    public function getCountryNameText()
    {
        if ($this->country_id)
            return $this->country_id;
        # we no longer use data_list
        #if ($this->country0)
        #    return $this->country0->name;
        return "";
    }

    public function getCountryName()
    {
        $countries = UCatalogo::listCountries();
        if ($this->country_id && isset($countries[$this->country_id]))
            return $countries[$this->country_id];
        return "";
    }

    public function getTypeName()
    {
        if ($this->organizationType)
            return $this->organizationType->name;
        return '';
    }

    public function getpadre()
    {
        if ($this->organization)
            return $this->organization->name;
        return '';
    }

    public function attributeLabels()
    {
        $array = parent::attributeLabels(); // TODO: Change the autogenerated stub
        $array['organization_id'] = Yii::t('app', 'Parent Organization');
        return $array;
    }
}
