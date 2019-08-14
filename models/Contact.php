<?php

namespace app\models;

use app\components\UCatalogo;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Query;

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

        $contact = null;
        if (!empty(trim($data['document']))) {
            $contact = self::find()->andFilterWhere(['document' => trim($data['document'])])->one();
        }
        if (is_null($contact)) {
            $contact = new self();
            $contact->created = date('Y-m-d');
        } else {
            $contact->modified = date('Y-m-d');
        }
        $contact->name = trim($data['name']);
        $contact->first_name = trim($data['first_name']);
        $contact->last_name = trim($data['last_name']);
        $contact->document = trim($data['document']);
        $contact->sex = trim($data['sex']);
        $contact->community = trim($data['community']);
        $contact->municipality = trim($data['municipality']);
        $contact->country_id = $data['country'];
        $contact->phone_personal = trim($data['phone_personal']);
        $contact->men_home = (int)$data['men_home'];
        $contact->women_home = (int)$data['women_home'];
        $contact->birthdate = $data['birthdate'];


        if (!$contact->education_id && !empty($data['education_name'])) {

            $education = MonitoringEducation::getSpecificEducation($data['education_name']);
            if (!is_null($education)) {
                $contact->education_id = $education->id;
            }
        }

        if (!$contact->organization_id && !empty($data['organization_name'])) {
            $org = Organization::find()->where(['name' => $data['organization_name']])->one();
            if (is_null($org)) {
                $org = new Organization();
                $org->name = $data['organization_name'];
                $org->save();
            }
            $contact->organization_id = $org->id;
        }

        $contact->save();

        $idContact = $contact->id;
        $projectContact = ProjectContact::find()->andFilterWhere(['project_id' => $project_id, 'contact_id' => $idContact])->one();

        if (is_null($projectContact)) {
            $projectContact = new ProjectContact();
        }

        $product = MonitoringProduct::getSpecificProduct($data['product']);
        $product_id = (int)$product->id;


        $projectContact->project_id = $project_id;
        $projectContact->contact_id = $idContact;
        $projectContact->product_id = $product_id;
        $projectContact->area = (int)$data['area'];
        $projectContact->development_area = (int)$data['development_area'];
        $projectContact->productive_area = (int)$data['productive_area'];
        $projectContact->age_development_plantation = (int)$data['age_development_plantation'];
        $projectContact->age_productive_plantation = (int)$data['age_productive_plantation'];
        $projectContact->date_entry_project = $data['date_entry_project'];
        $projectContact->yield = (int)$data['yield'];
        $projectContact->date_end_project = null;
        $projectContact->save();

        return $idContact;
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
                'organization_id' => Yii:t('app', 'Organization'),
                'organizationName' => Yii:t('app', 'Organization'),
                'educationName' => Yii:t('app', 'Education'),
                'monitor_id' => Yii:t('app', 'Monitor'),
                'monitorName' => Yii:t('app', 'Monitor'),
                'country_id' => Yii:t('app', 'Country'),
                'countryName' => Yii:t('app', 'Country'),
                'type_id' => Yii:t('app', 'Type'),
                'typeName' => Yii:t('app', 'Type'),
                'attendeeTypeName' => Yii:t('app', 'Tipo de Participante'),
                'otherPhones' => Yii:t('app', 'Otros Teléfonos'),
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
        if($this->type_id)
        {
            $type = MonitoringContactType::getSpecificTypeById($this->type_id);
            return $type->name;
        }
       
        return $this->type_id;
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
