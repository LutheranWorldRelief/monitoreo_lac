<?php

namespace app\models;

use Mpdf\Tag\Ul;
use Yii;
use app\components\Ulog;

/**
 * This is the model class for table "{{%event}}".
 *
 * Check the base class at app\models\base\Event in order to
 * see the column names and relations.
 */
class Event extends \app\models\base\Event
{
    public $attendancesArray = null;
    public $attendancesDelete = [];

    private $_attendancesModels = null;

    public function getImplementingOrganizationName()
    {
        if ($this->implementingOrganization)
            return $this->implementingOrganization->name;
        return '';
    }

    public function getCountryName()
    {
        if ($this->country)
            return $this->country->name;
        return '';
    }

    public function getstructure_name()
    {
        if ($this->structure)
            return $this->structure->nombre_largo;
        return '';
    }

    public function getproject_name()
    {
        if ($this->structure)
            return $this->structure->project_name;
        return '';
    }

    public function getproject_id()
    {
        if ($this->structure)
            return $this->structure->project_id;
        return '';
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge(
            $rules,
            [
                [['structure_id'], 'required'],
                [['attendancesArray'], 'validatorAttendances', 'skipOnEmpty' => false],
            ]
        );
    }

    public function validatorAttendances($attribute, $params)
    {
        $models = $this->attendancesModels;
        $validated = [];
        if (count($models) > 0) {
            foreach ($models as $model) {
                $model->event_id = $this->isNewRecord ? 1 : $this->id;
                if (!$model->validate()) {
                    $this->addError('attendancesArray', 'El registro de participante no es válido');
                }
                $validated[] = $model;
            }
        } else {
            $this->addError('attendancesArray', 'La lista debe tener al menos un participante');
        }
        $this->attendancesModels = $validated;
    }

    public function saveImport()
    {
        return parent::save(false);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $validEvent = true;
        $validAttendances = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $validEvent = parent::save();
            $models = $this->attendancesModels;
            foreach ($models as $m) {
                $m->event_id = $this->id;
                $validAttendances &= $m->save();
            }

            if (!$validAttendances) {
                $this->addError('attendancesArray', 'No se logró guardar el registro de participante');
            }

            foreach ($this->attendancesDelete as $m)
                $m->delete();

            if (!($validEvent && $validAttendances))
                throw new \yii\db\Exception("No se logró guardar la actividad");

            $transaction->commit();
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            $this->addError('id', $e->getMessage());
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        return $validEvent && $validAttendances;
    }

    public function delete()
    {
        $deletedAll = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->attendances as $key => $model)
                $deletedAll &= $model->delete();

            $deletedAll &= parent::delete();

            if (!$deletedAll)
                throw new \yii\db\Exception("No se logró eliminar esta actividad");

            $transaction->commit();
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $deletedAll;
    }

    //---------------------------------------------------------------------------- Attendances Models
    public function getAttendancesModels()
    {


        if ($this->_attendancesModels)
            return $this->_attendancesModels;

        $models = $this->attendances;
        $return = [];

        if ($this->attendancesArray && is_array($this->attendancesArray)) {
            foreach ($this->attendancesArray as $key => $attribs) {
                if (isset($attribs['id']) && !(is_null($attribs['id'] || $attribs['id'] == '')) && count($models) > 0) {
                    foreach ($models as $k => $model) {
                        if ($model->id == $attribs['id']) {
                            unset($models[$k]);
                            break;
                        }
                    }
                } else $model = new Attendance;

                $model->attributes = $attribs;
                $return[] = $model;
            }
        }

        $this->attendancesDelete = $models;
        $this->_attendancesModels = $return;

        return $return;
    }

    public function setAttendancesModels($value)
    {
        $this->_attendancesModels = $value;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(Attendance::className(), ['event_id' => 'id'])->with(['contact', 'contact.organization', 'org', 'type']);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id' => 'contact_id'])
            ->via('attendances');
    }

    public function getHombres()
    {
        return $this->getContacts()->where(['sex' => 'M'])->count();
    }

    public function getMujeres()
    {
        return $this->getContacts()->where(['sex' => 'F'])->count();
    }

    public function getAttendancesCount()
    {
        return $this->getContacts()->count();
    }

    public function getType()
    {
        return $this->hasOne(\app\models\DataList::className(), ['id' => 'type_id']);
    }

    public static function CreateFromImport($data, &$id = null)
    {
        /*
         * SE ESPERA QUE DATA TENGA UN ARREGLO, CON:
         *           CABECERA $data['cabecera'],
         *           DETALLES $data['detalles'],
         *           BANDERAS PARA REGISTRAR COMO NUEVO EL PROYECTO ($proyectoNuevo) Y ORGANIZACIÓN IMPLEMENTADORA ($organizacionNueva)
         *           DATOS DE PROYECTO $data['proyecto'] Y ORGANIZACIÓN IMPLEMENTADORA $data['organizacionImplementadora']
         * */

        $cabecera = [];
        $detalles = [];
        $proyectoNuevo = false;
        $organizacionNueva = false;
        $proyecto = [];
        $fechaIngreso = date('Y-m-d');
        $organizacionImplementadora = [];
        $proyectoId = null;
        $paisNombre = null;
        /*Extracción de las variables*/
        extract($data, null);
        $evento = new self();
        $evento->attributes = $cabecera;
        $evento->start = $fechaIngreso;
        $evento->end = $fechaIngreso;

        $evento->name = 'IMPORTACIÓN DESDE EXCEL POR ' . Yii::$app->getUser()->getIdentity()->first_name . ' ' . Yii::$app->getUser()->getIdentity()->last_name . ' El ' . date('Y-m-d H:i:s') . ' Con datos correspondientes al ' . $fechaIngreso . ' de la Organización ' . $organizacionImplementadora['name'] . ' del País ' . $paisNombre;
        $evento->implementing_organization_id = (int)$evento->implementing_organization_id;

        if ($proyectoNuevo) {
            $result = Project::CreateFromImport($proyecto);
            if (!is_null($result)) $evento->structure_id = $result['estructura']; else return false;
            $proyectoId = $result['proyecto'];
        } else {

            $estructura = Structure::find()->where(['project_id' => $proyectoId, 'description' => 'IMPORTACIÓN DESDE EXCEL'])->one();
            if (!$estructura) {
                $estructura = new Structure();
                $estructura->project_id = $proyectoId;
                $estructura->description = 'IMPORTACIÓN DESDE EXCEL';
                $estructura->save();
            }
            $evento->structure_id = $estructura->id;
        }

        if ($organizacionNueva) {
            $nombreOrg = $organizacionImplementadora['name'];
            $model = Organization::find()->where(['name' => $nombreOrg])->one();
            if (!$model) {
                $model = new Organization();
                $model->attributes = $organizacionImplementadora;
                $model->is_implementer = 1;
            }
            if ($model->save()) $evento->implementing_organization_id = $model->id; else return false;
        }

        $detallesValidos = true;
        $evento->validate();
        if ($evento->saveImport()) {
            foreach ($detalles as $d)
                $detallesValidos &= Attendance::CreateFromImport($d, $evento->id, $proyectoId);
            $id = $evento->id;
        } else return false;
        return $detallesValidos;
    }
}
