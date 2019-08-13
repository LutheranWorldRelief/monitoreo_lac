<?php

namespace app\models;

use Throwable;
use app\components\UCatalogo;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "{{%event}}".
 *
 * Check the base class at app\models\base\Event in order to
 * see the column names and relations.
 */
class Event extends base\Event
{
    public $attendancesArray = null;
    public $attendancesDelete = [];

    private $_attendancesModels = null;

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

        $evento->name = Yii::t('app', 'IMPORTACIÓN DESDE EXCEL POR {0} {1} El {2} Con datos correspondientes al {3} de la Organización {4} del País {5}',[$Yii::$app->getUser()->getIdentity()->first_name, Yii::$app->getUser()->getIdentity()->last_name, $date("Y-m-d H:i:s"), $fechaIngreso, $organizacionImplementadora["name"], $paisNombre);
        $evento->organization_id = (int)$evento->organization_id;


        if ($proyectoNuevo) {
            $result = Project::CreateFromImport($proyecto);
            if (!is_null($result)) $evento->structure_id = $result['estructura']; else return false;
            $proyectoId = $result['proyecto'];
        } else {
            $estructura = Structure::find()->where(['project_id' => $proyectoId, 'description' => Yii::t('app', 'IMPORTACIÓN DESDE EXCEL')])->one();
            $id_estructura = null;
            if (is_null($estructura)) {
                $estructura = new Structure();
                $estructura->project_id = $proyectoId;
                $estructura->description = Yii::t('app', 'IMPORTACIÓN DESDE EXCEL');
                $estructura->save();
                $id_estructura = $estructura->id;
            } else {
                $id_estructura = $estructura->id;
            }
            $evento->structure_id = $id_estructura;
        }

        if ($organizacionNueva)
        {
                $organization = new Organization();
                $organization->name = $organizacionImplementadora['name'];
                $organization->is_implementer = 1;

                $organization->save();
                 $evento->organization_id = $organization->id;
        }else
            {
            $nombreOrg = $organizacionImplementadora['name'];
            $organization = Organization::find()->where(['name' => $nombreOrg])->one();
            $id_organization = null;
            $evento->organization_id =  $organization->id;
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

    public function saveImport()
    {
        return parent::save(false);
    }

    public function getImplementingOrganizationName()
    {
        if ($this->implementingOrganization)
            return $this->implementingOrganization->name;
        return '';
    }

    public function getCountryName()
    {
        $countries = UCatalogo::listCountries();
        if ($this->country_id && isset($countries[$this->country_id]))
            return $countries[$this->country_id];
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
                    $this->addError('attendancesArray', Yii::t('app', 'El registro de participante no es válido'));
                }
                $validated[] = $model;
            }
        } else {
            $this->addError('attendancesArray', Yii::t('app', 'La lista debe tener al menos un participante'));
        }
        $this->attendancesModels = $validated;
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
                $this->addError('attendancesArray', Yii::t('app', 'No se logró guardar el registro de participante'));
            }

            foreach ($this->attendancesDelete as $m)
                $m->delete();

            if (!($validEvent && $validAttendances))
                throw new Exception(Yii::t('app', 'No se logró guardar la actividad'));

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->addError('id', $e->getMessage());
        } catch (Throwable $e) {
            $transaction->rollBack();
        }

        return $validEvent && $validAttendances;
    }

    //---------------------------------------------------------------------------- Attendances Models

    public function delete()
    {
        $deletedAll = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->attendances as $key => $model)
                $deletedAll &= $model->delete();

            $deletedAll &= parent::delete();

            if (!$deletedAll)
                throw new Exception(Yii::t('app', 'No se logró eliminar esta actividad'));

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $deletedAll;
    }

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
     * @return ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(Attendance::className(), ['event_id' => 'id'])->with(['contact', 'contact.organization', 'org', 'type']);
    }

    public function getHombres()
    {
        return $this->getContacts()->where(['sex' => 'M'])->count();
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id' => 'contact_id'])
            ->via('attendances');
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
        return $this->hasOne(DataList::className(), ['id' => 'type_id']);
    }
}
