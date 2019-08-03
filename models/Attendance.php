<?php

namespace app\models;

use app\components\Ulog;
use app\components\UString;
use Throwable;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "{{%attendance}}".
 *
 * Check the base class at app\models\base\Attendance in order to
 * see the column names and relations.
 */
class Attendance extends base\Attendance
{
    public $fullname = "";
    public $org_name = "";
    public $type_name = "";

    public static function CreateFromImport($data, $event_id, $project_id)
    {
        $model = new  self();
        $model->event_id = $event_id;
        $model->country_id = $data['country'];
        $id = Contact::CreateFromImport($data, $project_id);
        $model->contact_id = (int)$id;
        return $model->save(false);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $org = Organization::find()->where(['id' => (int)$this->organization_id])->one();

        if (!$org && $this->org_name && !$this->organization_id) {
            $org = Organization::find()->where(['name' => $this->org_name])->one();
            if (!$org) {
                $org = new Organization;
                $org->is_implementer = 0;
                $org->name = $this->org_name;
            }
        }
        $contact = Contact::find()->where(['id' => (int)$this->contact_id])->one();
        if (!$contact) {
            if ($this->fullname) {
                $contact = new Contact;
                $contact->attributes = $this->attributes;
                $contact->name = UString::upperCase($this->fullname);
            }
        }

        $return = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($org && $org->isNewRecord) {
                if ($return &= $org->save())
                    $this->organization_id = $org->id;
                else {
                    Ulog::l([$org->errors]);
                    throw new Exception("No se logró guardar el registro de organización");
                }
            }

            if ($contact) {
                if ($org)
                    $contact->organization_id = $org->id;

                if ($return &= $contact->save())
                    $this->contact_id = $contact->id;
                else {
                    $this->addErrors($contact->errors);
                    throw new Exception("No se logró guardar el registro del contacto");
                }
            }

            $return &= parent::save();

            if (!$return) {
                throw new Exception("No se logró guardar el registro de participante");
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->addError('contact_id', $e->getMessage());
        } catch (Throwable $e) {
            $transaction->rollBack();
        }
        return $return;
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge(
            [
                [['event_id', 'type_id', 'contact_id', 'organization_id'], 'integer'],
                [['date'], 'safe'],
                [['document', 'country_id', 'phone_personal'], 'string', 'max' => 45],
                [['sex'], 'string', 'max' => 1],
                [['community'], 'string', 'max' => 255],
            ],
            [
                [['fullname'], 'required'],
                [['contact_id', 'organization_id', 'org_name', 'document', 'sex', 'country_id', 'community', 'phone_personal', 'event_id', 'type_id'], 'safe'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        return array_merge(
            $labels,
            [
                'fullname' => 'Nombre',
                'type_id' => 'Type',
                'type_name' => 'Type',
            ]
        );
    }

    public function afterFind()
    {
        parent::afterFind();

        if ($this->org)
            $this->org_name = $this->org->name;

        if ($this->type)
            $this->type_name = $this->type->name;

        if (!$this->type_name && $this->attendanceType)
            $this->type_name = $this->attendanceType->name;

        if ($this->contact) {
            $this->fullname = $this->contact->fullname;
            if (!$this->document) $this->document = $this->contact->document;
            if (!$this->sex) $this->sex = $this->contact->sex;
            if (!$this->country_id) $this->country_id = $this->contact->country_id;
            if (!$this->community) $this->community = $this->contact->community;
            if (!$this->phone_personal) $this->phone_personal = $this->contact->phone_personal;
            if (!$this->org_name) {
                $this->organization_id = $this->contact->organization_id;
                $this->org_name = $this->contact->organizationName;
            }
        }

        return true;
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields = array_merge(
            $fields,
            [
                'fullname',
                'org_name',
            ]
        );

        return $fields;
    }

    public function get_errors()
    {
        return $this->errors;
    }

    public function getOrg()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    public function getType()
    {
        return $this->hasOne(DataList::className(), ['id' => 'type_id']);
    }

    public function getAttendanceType()
    {


        return $this->hasOne(DataList::className(), ['id' => 'type_id']);
    }
}
