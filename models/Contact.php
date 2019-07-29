<?php

namespace app\models;

use app\components\UCatalogo;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "{{%contact}}".
 *
 * Check the base class at app\models\base\Contact in order to
 * see the column names and relations.
 */
class Contact extends base\Contact
{
    public static function CreateFromImport($data, $project_id)
    {
        $model = null;
        if (!empty(trim($data['document']))) {
            $model = self::find()->andFilterWhere(['document' => trim($data['document'])])->one();
        }
        if (!$model)
            $model = new self();
        $model->attributes = $data;


        if (!$model->education_id && !empty($data['education_name'])) {
            $education = DataList::idItemBySlug('education', $data['education_name']);
            if ($education == null)
                $education = DataList::CreateItem('education', $data['education_name']);
            $model->education_id = $education;
        }

        if (!$model->organization_id && !empty($data['organization_name'])) {
            $org = Organization::find()->where(['name' => $data['organization_name']])->one();
            if (!$org) {
                $org = new Organization();
                $org->name = $data['organization_name'];
                $org->save();
            }
            $model->organization_id = $org->id;
        }
        if ($model->save()) {
            $proyecto = ProjectContact::find()->andFilterWhere(['project_id' => $project_id, 'contact_id' => $model->id])->one();
            if (!$proyecto)
                $proyecto = new ProjectContact();
            $proyecto->attributes = $data;
            $proyecto->project_id = $project_id;
            $proyecto->contact_id = $model->id;
            if ($proyecto->save())
                return $model->id;
        }
        return $model->id;
    }

    public function rules()
    {
        $rules = parent::rules();
        foreach ($rules as $key => $rule) {
            if ($rule[1] == 'exist') unset($rules[$key]);
        }
        return $rules;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        return array_merge(
            $labels, [
                'organization_id' => 'Organization',
                'organizationName' => 'Organization',
                'educationName' => 'Education',
                'monitor_id' => 'Monitor',
                'monitorName' => 'Monitor',
                'country_id' => 'Country',
                'countryName' => 'Country',
                'type_id' => 'Type',
                'typeName' => 'Type',
                'attendeeTypeName' => 'Tipo de Participante',
                'otherPhones' => 'Otros Teléfonos',
            ]
        );
    }

    public function afterFind()
    {
        parent::afterFind();
        if (!$this->name) {
            $this->name = $this->fullname;
        }
    }

    public function getCountryName()
    {
        $countries = UCatalogo::listCountries();
        if ($this->country_id && isset($countries[$this->country_id]))
            return $countries[$this->country_id];
        return "";
    }

    public function getOtherPhones()
    {
        $data = "";
        if ($this->listPhones)
            $data = $this->listPhones->phone_personal;
        $saved = $this->phone_personal;
        $data = trim(str_replace($saved, "", trim(str_replace($saved . ",", "", $data))));
        $saved = $this->phone_work;
        $data = trim(str_replace($saved, "", trim(str_replace($saved . ",", "", $data))));
        return $data;
    }

    public function getOrganizationName()
    {
        if ($this->organization)
            return $this->organization->name;
        return "";
    }

    public function getEducationName()
    {
        if ($this->education)
            return $this->education->name;
        return "";
    }

    public function getTypeName()
    {
        if ($this->type)
            return $this->type->name;
        return "";
    }

    public function getAttendeeTypeName()
    {
        if ($this->type)
            return $this->type->name;
        if ($this->type)
            return $this->type->name;
        return "";
    }

    public function getFullname()
    {
        if ($this->name)
            return $this->name;

        return trim(trim($this->first_name) . " " . $this->last_name);
    }

    public function getProfessionName()
    {
        if ($this->profession)
            return $this->profession->name;
        return "";
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->name)
            $this->name = $this->fullname;

        return parent::save($runValidation, $attributeNames);
    }

    public function getAttendeeType()
    {
        return $this->hasOne(Attendeetype::className(), ['id' => 'type_id']);
    }

    public function getType()
    {
        return $this->hasOne(DataList::className(), ['id' => 'type_id']);
    }

    public function getListPhones()
    {
        return $this->hasOne(SqlContactListPhonesGroup::className(), ['contact_id' => 'id']);
    }

    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['id' => 'event_id'])
            ->via('attendances');
    }

    public function getStructures()
    {
        return $this->hasMany(Structure::className(), ['id' => 'structure_id'])
            ->via('events');
    }

    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id' => 'project_id'])
            ->via('structures');
    }

    public function delete()
    {
        $return = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->attendances as $att)
                $return &= $att->delete();

            $return &= parent::delete();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return $return;
    }

    /* -------------------------------------------------------------------------------------- RELATIONS */

    /**
     * @return ActiveQuery
     */
    public function getProjectContactOne()
    {
        return $this->hasOne(ProjectContact::className(), ['contact_id' => 'id']);
    }

}
