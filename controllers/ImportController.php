<?php

namespace app\controllers;


use app\components\Controller;
use app\components\excel\import\ImportBehavior;
use app\components\UExcelBeneficiario;
use app\components\UString;
use app\models\Contact;
use app\models\Country;
use app\models\DataList;
use app\models\Event;
use app\models\MonitoringProduct;
use app\models\Organization;
use app\models\MonitoringEducation;
use app\models\Project;
use app\models\ProjectContact;
use Exception;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii2mod\query\ArrayQuery;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ImportController extends Controller
{
    public $archivoBeneficiarios = null;
    public $erroresBeneficiarios = [];
    private $_import_codigos_proyectos = [];

    public function behaviors()
    {
        $comportamientos = parent::behaviors();
        $comportamientos[] = ImportBehavior::className();
        return $comportamientos;
    }

    /*La funcion onImportRowBeneficiarios se ejecuta por cada fila que es leida del excel */

    public function onReadAllDataExcelBeneficiarios()
    {
        if (!$this->archivoValido)
            return;
        $log = [
            'Correcto' => $this->getImportSuccessLog(),
            'Incorrecto' => $this->getImportErrorLog(),
            'Guardar' => $this->getDataGuardar()
        ];

        $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');
        $archivo = date('Ymd-His_') . Yii::$app->user->id . '_beneficiarios.json';
        file_put_contents($pathImportJson . $archivo, Json::encode($log));
        $this->redirect(['import/beneficiarios-paso2', 'archivo' => $archivo]);
    }

    public function onImportRowBeneficiarios($row, $index, $max_row)
    {
        Yii::warning([$row, $index, $max_row]);

        /*$key almacena las posición de los campos en el row*/
        $key = UExcelBeneficiario::getCamposPosicion();

        /*si es el último registro se cancela*/
        if ((int)$index > (int)$max_row)
            return false;

        /*Los encabezados de los registros deben venir en la segunda fila*/
        if ((int)$index == 2) {
            /*Valida que el excel sea el correcto y que tenga las columnas correctas*/
            $errores = [];
            $this->archivoValido = UExcelBeneficiario::verificaCamposEnArreglo($row, $errores);
            $this->erroresBeneficiarios = $errores;
            if (!$this->archivoValido)
                return false;
        }
        if ((int)$index > 2) {

            /* Valida que la fila no esté vacia y evitar recorrer el libro entero */
            if ((empty($row[$key['nombres']]) || is_null($row[$key['nombres']])) && (empty($row[$key['sexo']]) || is_null($row[$key['sexo']])))
                return false;

            $totalRow = ((int)$max_row - 1);
            $log = (" ( Fila $index de $totalRow) " . implode(', ', $row));

            $this->addLog($log);

            $errorSexo = false;
            if ($row[$key['sexo']] === 'Hombre' || $row[$key['sexo']] == 'Mujer')
                $sex = ($row[$key['sexo']] === 'Hombre') ? 'M' : 'F';
            else {
                $sex = 'error';
                $errorSexo = true;
            }

            /*Conversión de fechas excel a fecha php*/
            try {
                $date = $row[$key['nacimiento']];
                if (!empty($date) && ($date !== ' ')) $nacimiento = $this->getFechaExcel($date);
                else $nacimiento = null;

            } catch (Exception $e) {
                $date = str_replace('/', '-', $row[$key['nacimiento']]);
                if (!empty($date) && ($date !== ' ')) $nacimiento = date('Y-m-d', strtotime($date));
                else $nacimiento = null;
            }
            try {
                $date = $row[$key['ingreso_proyecto']];
                if (!empty($date) && ($date !== ' ')) $ingresoProyecto = $this->getFechaExcel($date);
                else $ingresoProyecto = null;
            } catch (Exception $e) {
                $date = str_replace('/', '-', $row[$key['ingreso_proyecto']]);
                if (!empty($date) && ($date !== ' ')) $ingresoProyecto = date('Y-m-d', strtotime($date));
                else $ingresoProyecto = null;
            }
            //obtener el id la organizacion en busqueda
            $organizationId = Organization::getIdFromName($row[$key['organizacion']]);
            //obtener el id de la organizacion que implemento
            $implementingOrganizationId = Organization::getIdFromName($row[$key['organizacion_implementadora']]);
            //obtener el id del pais
            $countryCode = DataList::CountryCode($row[$key['pais']]);

            $education = MonitoringEducation::getSpecificEducation($row[$key['educacion']]);

            $educationId = null;
            if (!is_null($education)) {
                $educationId = $education->id;
            }

            $mujeres = (int)$row[$key['mujeres']];
            if (gettype($row[$key['mujeres']]) === 'string') {
                $mujeres = null;
            }

            $hombres = (int)$row[$key['hombres']];
            if (gettype($row[$key['hombres']]) === 'string') {
                $hombres = null;
            }

            /*Busca el proyecto en la base de datos y si lo encuentra proyectoId regresa con valor*/
            $proyectoId = null;
            $this->importSetIdProyecto($proyectoId, $proyectoCodigo, $proyectoNombre, explode('=>', $row[0]));
            /*traducción de la fila de excel a campos de la base de datos*/
            $fila = [
                'project_id' => $proyectoId,
                'project_code' => preg_replace('/\s+/', ' ', TRIM($proyectoCodigo)),
                'project_name' => preg_replace('/\s+/', ' ', TRIM($proyectoNombre)),
                'implementing_organization_id' => $implementingOrganizationId,
                'implementing_organization_name' => $row[$key['organizacion_implementadora']],
                'document' => (string)$row[$key['identificacion']],
                'name' => preg_replace('/\s+/', ' ', $row[$key['nombres']] . ' ' . $row[$key['apellidos']]),
                'first_name' => preg_replace('/\s+/', ' ', (string)$row[$key['nombres']]),
                'last_name' => preg_replace('/\s+/', ' ', (string)$row[$key['apellidos']]),
                'sex' => $sex,
                'birthdate' => $nacimiento,
                'education_id' => $educationId,
                'education_name' => $row[$key['educacion']],
                'phone_personal' => (string)$row[$key['telefono']],
                'men_home' => $hombres,
                'women_home' => $mujeres,
                'organization_id' => $organizationId,
                'organization_name' => $row[$key['organizacion']],
                'country' => $countryCode,
                'country_name' => $row[$key['pais']],
                'municipality' => $row[$key['departamento']],
                'community' => $row[$key['comunidad']],
                'date_entry_project' => $ingresoProyecto,
                'product' => $row[$key['rubro']],
                'area' => $row[$key['area']],
                'development_area' => $row[$key['area_desarrollo']],
                'age_development_plantation' => $row[$key['edad_desarrollo']],
                'productive_area' => $row[$key['area_produccion']],
                'age_productive_plantation' => $row[$key['edad_produccion']],
                'yield' => $row[$key['rendimiento']],
            ];

            $beneficiario = new Contact();
            $beneficiario->attributes = $fila;

            $proyectoValido = true;
            if (is_null($proyectoId) || empty($proyectoId)) $proyectoValido = false;

            $fechaIngresoValida = true;
            if ($ingresoProyecto === null || strtotime($ingresoProyecto) < strtotime('1980-01-01')) $fechaIngresoValida = false;

            $implementingOrganizationValida = $implementingOrganizationId ? true : false;

            if ($beneficiario->validate() & $countryCode !== null & $proyectoValido & !empty($row[$key['organizacion_implementadora']]) & $fechaIngresoValida & $implementingOrganizationValida) {
                $this->addSuccessLog($log);
                $this->addDataGuardar($fila);
            } else {
                $errorText = "<h3>Fila $index de $totalRow </h3>";

                if (!$countryCode)
                    $errorText .= '<br><b style="color:#dd4b39">' . Yii::t('app', 'Debe establecer una país válido') . '</b>';
                if (!$fechaIngresoValida)
                    $errorText .= '<br><b style="color:#dd4b39">' . Yii::t('app', 'Debe establecer una fecha de ingreso válida') . '</b>';
                if (!$proyectoValido)
                    $errorText .= '<br><b style="color:#dd4b39">' . Yii::t('app', 'Error en proyecto, verifiqué que el proyecto exista') . '</b>';
                if (empty($row[$key['organizacion_implementadora']]))
                    $errorText .= '<br><b style="color:#dd4b39">' . Yii::t('app', 'Error en Organización Implementadora') . '</b>';
                if (!$implementingOrganizationValida)
                    $errorText .= '<br><b style="color:#dd4b39">' . Yii::t('app', 'Debe establecer una organización implementadora que exista en la base de datos') . '</b>';
                if ($errorSexo)
                    $errorText .= '<br><b style="color:#dd4b39">' . Yii::t('app', 'Error en Sexo, se espera Hombre o Mujer') . '</b>';
                if (!$beneficiario->validate())
                    $errorText .= '<br><b style="color:#dd4b39">' . Yii::t('app', 'Error Beneficiarios') . implode(', ', $beneficiario->getFirstErrors()) . '</b>';
                $errorText .= "<br>" . implode(', ', $row);
                $this->addErrorLog($errorText);
            }
        }
        return true; // return FALSE to stop import
    }

    /*
     * recepción de idProyecto por referencia para modificar su valor sin retornarlo
     *
     */

    private function importSetIdProyecto(&$proyectoId, &$proyectoCodigo, &$proyectoNombre, $proyecto)
    {
        $proyectoCodigo = $proyecto[0];
        try {
            $proyectoNombre = $proyecto[1];
        } catch (Exception $e) {
            $proyectoNombre = null;
            $proyectoCodigo = null;
            $proyectoId = null;
        }
        if (array_key_exists($proyectoCodigo, $this->_import_codigos_proyectos))
            $proyectoId = $this->_import_codigos_proyectos[$proyectoCodigo];
        else {
            $modeloProyecto = Project::find()->where(['code' => $proyectoCodigo])->one();
            if ($modeloProyecto)
                $proyectoId = $modeloProyecto->id;
            else
                $proyectoId = null;
            $this->_import_codigos_proyectos[$proyectoCodigo] = $proyectoId;
        }
    }

    public function actionBeneficiariosPaso1()
    {
        set_time_limit(-1);
        ini_set('memory_limit', -1);
        $this->onImportRow = 'onImportRowBeneficiarios';
        $this->onSaveData = 'onReadAllDataExcelBeneficiarios';
        $this->importExcel();
        return $this->render('beneficiarios/wizard', ['data' => ['valido' => $this->archivoValido, 'errores' => $this->erroresBeneficiarios], 'view' => 'step-1', 'stepActive' => 'step1']);
    }

    /*--------------PASO 2 PARA IMPORTACIÓN DE BENEFICIARIOS--------------*/

    public function actionBeneficiariosPaso2($archivo)
    {
        $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');

        $resultados = Json::decode(file_get_contents($pathImportJson . $archivo));

        $errores = $this->BeneficiariosPaso2Guardar($resultados);

        $this->BeneficiariosPaso2DatosCrearEnBD($resultados, $proyectosRegistrar, $organizacionRegistrar, $paisesRegistrar, $educacionRegistrar);


        $data = [
            'data' => $resultados,
            'errores' => $errores,
            'proyectosRegistrar' => $proyectosRegistrar,
            'organizacionRegistrar' => $organizacionRegistrar,
            'paisesRegistrar' => $paisesRegistrar,
            'educacionRegistrar' => $educacionRegistrar,
        ];

        return $this->render('beneficiarios/wizard', ['data' => $data, 'view' => 'step-2', 'stepActive' => 'step2']);
    }

    private function BeneficiariosPaso2Guardar($resultados)
    {
        $projectContact = [];
        $errores = null;
        if (isset($_POST['guardar'])) {
            if (!empty($_POST['pais']) && !is_null($_POST['pais'])) {

                $this->BeneficiariosPaso2VincularDatos($datosVincular, $resultados);
                if ($this->insertarDatosProvenientesExcel($datosVincular, $projectContact)) {
                    $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');
                    if (!isset($_GET['archivo'])) {
                        unlink($pathImportJson . $_GET['archivo']);
                    }
                    $archivo = date('Ymd-His_') . Yii::$app->user->id . '_projectsContactsId.json';
                    file_put_contents($pathImportJson . $archivo, Json::encode($projectContact));
                    $this->redirect(['import/beneficiarios-paso3', 'archivo' => $archivo]);
                }
            } else {
                $errores = Yii::t('app', 'Debe seleccionar el país de la importación.');
            }
        } //exit();
        return $errores;
    }

    private function BeneficiariosPaso2VincularDatos(&$datosVincular, $resultados)
    {
        $datosVincular = [];
        foreach ($resultados['Guardar'] as $r) {
            if (isset($_POST['organizacion']))
                foreach ($_POST['organizacion'] as $org) {

                    if (!empty($org['vincular_con']) && $org['nombre'] == $r['implementing_organization_name'])
                        $r['organization_id'] = $org['vincular_con'];

                    if (!empty($org['vincular_con']) && $org['nombre'] == $r['organization_name'])
                        $r['organization_id'] = $org['vincular_con'];
                }
            if (isset($_POST['educacion']))
                foreach ($_POST['educacion'] as $edu)
                    if (!empty($edu['vincular_con']) && $edu['nombre'] == $r['education_name'])
                        $r['education_id'] = $edu['vincular_con'];

            $key = $r['project_code'] . '-' . UString::sustituirEspacios($r['implementing_organization_name']) . '-' . $r['date_entry_project'];
            $datosVincular[$key]['proyectoId'] = (int)$r['project_id'];
            $datosVincular[$key]['proyecto'] = ['code' => $r['project_code'], 'name' => $r['project_name']];
            $datosVincular[$key]['organizacionImplementadora'] = $r['implementing_organization_name'];
            if (!isset($datosVincular[$key]['detalles']))
                $datosVincular[$key]['detalles'] = [];
            $datosVincular[$key]['detalles'][] = $r;

        }
    }

    private function insertarDatosProvenientesExcel($data, &$projectContacts)
    {
        set_time_limit(-1);
        ini_set('memory_limit', -1);
        $nextStep = false;
        try {
            foreach ($data as $datum => $d) {
                $organizationName = $d['organizacionImplementadora'];
                $organization = Organization::find()->andFilterWhere(['name' => trim($organizationName)])->one();
                $organization_id = $organization->id;
                $detalles = $d['detalles'];
                foreach ($detalles as $detalle => $de) {
                    $contact = null;
                    $document = trim($de['document']);
                    //verificamos si existe un contacto registrado con el documento digitado el excel
                    if (!empty($document)) {
                        $contact = Contact::find()->andFilterWhere(['document' => $document])->one();
                    }
                    /*
                     * si no se encontro un contacto con ese documento, se instancia una
                     * a la clase contact para crear un nuevo registro
                    */
                    if (is_null($contact)) {
                        $contact = new Contact();
                        $contact->created = date('Y-m-d');
                    } else {
                        $contact->modified = date('Y-m-d');
                    }
                    $contact->name = trim($de['name']);
                    $contact->first_name = trim($de['first_name']);
                    $contact->last_name = trim($de['last_name']);
                    $contact->document = trim($de['document']);
                    $contact->sex = trim($de['sex']);
                    $contact->community = trim($de['community']);
                    $contact->municipality = trim($de['municipality']);
                    $contact->country_id = $de['country'];
                    $contact->phone_personal = trim($de['phone_personal']);
                    $contact->men_home = (int)$de['men_home'];
                    $contact->women_home = (int)$de['women_home'];
                    $contact->birthdate = $de['birthdate'];

                    /*
                     * valido si el contacto no tiene una eduacion y el campo educacion
                     * en el excel no este vacio para obtener el id de esa educacion en la bd
                     * y indicar el id obtenido
                    */
                    if (!is_null($contact->education_id) && !empty(trim($de['education_name']))) {
                        $education = MonitoringEducation::getSpecificEducation(trim($de['education_name']));
                        if (!is_null($education)) {
                            $contact->education_id = $education->id;
                        }
                    }

                    /*
                     * valido si el contacto no tiene una organizacion y el campo organizacion
                     * en el excel no este vacio para obtener el id de esa organizacion en la bd
                     * y indicar el id obtenido
                    */
                    if (!is_null($contact->organization_id) && !empty(trim($de['organization_name']))) {
                        $org = Organization::find()->where(['name' => trim($de['organization_name'])])->one();
                        if (is_null($org)) {
                            $org = new Organization();
                            $org->name = trim($de['organization_name']);
                            if (!is_null($de['organization_id'])) {
                                $org->organization_id = (int)$de['organization_id'];
                            }
                            $org->save();
                        }
                        $contact->organization_id = $org->id;
                    }
                    //guardamos el contacto
                    $contact->save();
                    //guardamos el id del contacto nuevo o actualizado
                    $idContact = $contact->id;

                    //obtenemos el id del producto seleccionado
                    $product = MonitoringProduct::getSpecificProduct($de['product']);
                    $product_id = $product->id;

                    //guardamos el project contact
                    $project_id = $de['project_id'];
                    $projectContact = ProjectContact::find()->andFilterWhere(['project_id' => $project_id, 'contact_id' => $idContact])->one();
                    if (is_null($projectContact)) {
                        $projectContact = new ProjectContact();
                    }

                    $projectContact->project_id = $project_id;
                    $projectContact->contact_id = $idContact;
                    $projectContact->product_id = $product_id;
                    $projectContact->area = (int)$de['area'];
                    $projectContact->development_area = (int)$de['development_area'];
                    $projectContact->productive_area = (int)$de['productive_area'];
                    $projectContact->age_development_plantation = (int)$de['age_development_plantation'];
                    $projectContact->age_productive_plantation = (int)$de['age_productive_plantation'];
                    $projectContact->date_entry_project = $de['date_entry_project'];
                    $projectContact->date_end_project = $de['date_entry_project'];
                    $projectContact->yield = (int)$de['yield'];
                    $projectContact->organization_id = $organization_id;
                    $projectContact->save();
                    array_push($projectContacts, ['idprojectcontact' => $projectContact->id]);
                    $nextStep = true;
//                    $i++;
                }

            }
//            exit();

            return $nextStep;

        } catch (Exception $e) {
            var_dump($e);
            return $nextStep;
        }

    }

    private function BeneficiariosPaso2GuardarContactos($data, &$archivo)
    {
        set_time_limit(-1);
        ini_set('memory_limit', -1);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($eventos as $evento) {
                $id = null;
                if (!Event::CreateFromImport($evento, $id)) {

                } else {
                    $eventosCreados[] = $id;
                }

            }
            $transaction->commit();

        } catch (Exception $exception) {
            $transaction->rollBack();
        }

        if (isset($_GET['archivo'])) {
            $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');
            unlink($pathImportJson . $_GET['archivo']);
        }

        $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');
        $archivo = date('Ymd-His_') . Yii::$app->user->id . '_eventos.json';
        file_put_contents($pathImportJson . $archivo, Json::encode($eventosCreados));

        return true;
    }

    private function BeneficiariosPaso2DatosCrearEnBD($resultados, &$proyectosRegistrar, &$organizacionRegistrar, &$paisesRegistrar, &$educacionRegistrar)
    {
        $query = new ArrayQuery();
        $query->from($resultados['Guardar']);

        $proyectos = clone $query;
        $proyectos->where(['project_id' => null]);
        $proyectosRegistrar = [];
        foreach ($proyectos->all() as $model) {
            $proyectosRegistrar [$model['project_code']] = [
                'project_code' => $model['project_code'],
                'project_name' => $model['project_name']
            ];
        }

        $paises = clone $query;
        $paises->where(['country' => null]);
        $paisesRegistrar = [];
        foreach ($paises->all() as $model) {
            if (!empty($model['country_name']))
                $paisesRegistrar [$model['country_name']] = [
                    'country_name' => $model['country_name']
                ];
        }

        $educacion = clone $query;
        $educacion->where(['education_id' => null]);
        $educacionRegistrar = [];
        foreach ($educacion->all() as $model) {
            if (!empty($model['education_name']))
                $educacionRegistrar [$model['education_name']] = [
                    'education_name' => $model['education_name']
                ];
        }

        $organizacion = clone $query;
        $organizacion->andFilterWhere(['or', 'organization_id', null]);
        $organizacion->andFilterWhere(['or', 'implementing_organization_id', null]);
        $organizacionRegistrar = [];

        foreach ($organizacion->all() as $model) {
            if (is_null($model['implementing_organization_id']))
                if (!empty($model['implementing_organization_name']))
                    $organizacionRegistrar [$model['implementing_organization_name']] = [
                        'organization_name' => $model['implementing_organization_name']
                    ];

            if (is_null($model['organization_id']))
                if (!empty($model['organization_name']))
                    $organizacionRegistrar [$model['organization_name']] = [
                        'organization_name' => $model['organization_name']
                    ];
        }
    }

    public function actionBeneficiariosPaso3_($archivo)
    {
        try {
            $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');
            $resultados = Json::decode(file_get_contents($pathImportJson . $archivo));
            $eventos = Event::find()->andFilterWhere(['in', 'id', $resultados])->with(['implementingOrganization', 'structure', 'structure.project'])->asArray()->all();
            $data = ['data' => $eventos,];
            return $this->render('beneficiarios/wizard', ['data' => $data, 'view' => 'step-3', 'stepActive' => 'step3']);
        } catch (Exception $exception) {
            var_dump($exception);
//            $this->redirect('beneficiarios-paso1');
        }

    }

    public function actionBeneficiariosPaso3($archivo)
    {
        $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');
        $resultados = Json::decode(file_get_contents($pathImportJson . $archivo));

        $idprojectcontact = [];
        foreach ($resultados as $resultado) {
            $idprojectcontact[] = $resultado['idprojectcontact'];
        }

        $queryDocumentos = (new Query());
        $queryDocumentos->select(['btrim(replace(replace(contact.document, \'-\' :: text, \'\' :: text), \' \' :: text, \'\' :: text)) AS doc_id'])
            ->from('public.contact')
            ->where(['<>', 'COALESCE(contact.document, \'\' :: text)', ''])
            ->groupBy(['btrim(replace(replace(contact.document, \'-\' :: text, \'\' :: text), \' \' :: text, \'\' :: text))'])
            ->having(['>', 'COUNT(contact.id)', 1]);
        $documentos = $queryDocumentos->column();

        $queryNombres = (new Query());
        $queryNombres->select(['btrim(upper(COALESCE(
        replace(replace(replace(contact.name::text, \' \'::text, \'<>\'::text), \'><\'::text, \'\'::text), \'<>\'::text,
                \' \'::text), concat(btrim(concat(btrim(contact.first_name::text), \'\')), \' \',
                                   btrim(COALESCE(contact.last_name::text, \'\'::text)))))) AS name'])
            ->from('public.contact')
            ->groupBy(['btrim(upper(COALESCE(replace(replace(replace(contact.name::text, \' \'::text, \'<>\'::text), \'><\'::text, \'\'::text), \'<>\'::text,
                \' \'::text), concat(btrim(concat(btrim(contact.first_name::text), \'\')), \' \',
                                   btrim(COALESCE(contact.last_name::text, \'\'::text))))))'])
            ->having(['>', 'COUNT(contact.id)', 1]);
        $nombres = $queryNombres->column();

        $queryContact = (new Query());
        $queryContact->select(['c.id as contact_id',
            'trim(upper(c.name)) as contact_name',
            'c.sex as contact_sex',
            'c.document as contact_document',
            'o.name as contact_organization'])
            ->from('project p')
            ->leftJoin('project_contact pc', 'p.id = pc.project_id')
            ->leftJoin('contact c', 'pc.contact_id = c.id')
            ->leftJoin('organization o', 'c.organization_id = o.id')
            ->orWhere(['in', "trim( REPLACE ( REPLACE (c.document, '-', '' ), ' ', '' ) )", $documentos])
            ->orWhere(['in', "trim(upper(replace(replace(replace(c.name,' ','<>'),'><',''),'<>',' ')))", $nombres])
            ->andWhere(['in', 'pc.id', $idprojectcontact])
            ->andWhere('c.document is not null');
//            ->groupBy(['contact_name', "contact_sex", "contact_document", "contact_organization", 'contact_id']);
        $personas = $queryContact->all();
        $data = ['data' => $personas];
        return $this->render('beneficiarios/wizard', ['data' => $data, 'view' => 'step-3', 'stepActive' => 'step3']);

    }


}
