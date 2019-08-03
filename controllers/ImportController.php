<?php

namespace app\controllers;


use app\components\Controller;
use app\components\excel\import\ImportBehavior;
use app\components\UExcelBeneficiario;
use app\components\UString;
use app\models\Contact;
use app\models\DataList;
use app\models\Event;
use app\models\Organization;
use app\models\Project;
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

            $organizationId = Organization::getIdFromName($row[$key['organizacion']]);
            $implementingOrganizationId = Organization::getIdFromName($row[$key['organizacion_implementadora']]);
            $countryCode = DataList::CountryCode($row[$key['pais']]);
            $educationId = DataList::idItemBySlug('education', $row[$key['educacion']]);

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
                'men_home' => $row[$key['hombres']],
                'women_home' => $row[$key['mujeres']],
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
                    $errorText .= '<br><b style="color:#dd4b39">Debe establecer una país válido</b>';
                if (!$fechaIngresoValida)
                    $errorText .= '<br><b style="color:#dd4b39">Debe establecer una fecha de ingreso válida</b>';
                if (!$proyectoValido)
                    $errorText .= '<br><b style="color:#dd4b39">Error en proyecto, verifiqué que el proyecto exista</b>';
                if (empty($row[$key['organizacion_implementadora']]))
                    $errorText .= '<br><b style="color:#dd4b39">Error en Organización Implementadora</b>';
                if (!$implementingOrganizationValida)
                    $errorText .= '<br><b style="color:#dd4b39">Debe establecer una organización implementadora que exista en la base de datos</b>';
                if ($errorSexo)
                    $errorText .= '<br><b style="color:#dd4b39">Error en Sexo, se espera Hombre o Mujer </b>';
                if (!$beneficiario->validate())
                    $errorText .= '<br><b style="color:#dd4b39">Error Beneficiarios ' . implode(', ', $beneficiario->getFirstErrors()) . ' </b>';
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
        // try {
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
        //  } catch (Exception $exception) {
        var_dump($exception);
//            $this->redirect('beneficiarios-paso1');
        //  }

    }

    private function BeneficiariosPaso2Guardar($resultados)
    {
        $archivo = null;
        $errores = null;
        if (isset($_POST['guardar'])) {
            if (!empty($_POST['pais']) && !is_null($_POST['pais'])) {
                $this->BeneficiariosPaso2ConstruirEventos($eventos, $resultados);

                if ($this->BeneficiariosPaso2GuardarEventos($eventos, $archivo)) {
                    echo "Bene";
                    $this->redirect(['import/beneficiarios-paso3', 'archivo' => $archivo]);
                }
            } else {
                $errores = 'Debe seleccionar el país de la importación.';
            }
        } //exit();
        return $errores;
    }

    private function BeneficiariosPaso2ConstruirEventos(&$eventos, $resultados)
    {
        $eventos = [];
        $pais = DataList::find()->where(['value' => $_POST['pais']])->one();
        $paisNombre = $pais ? $pais->name : '-';
        foreach ($resultados['Guardar'] as $r) {
            if (isset($_POST['organizacion']))
                foreach ($_POST['organizacion'] as $org) {

                    if (!empty($org['vincular_con']) && $org['nombre'] == $r['implementing_organization_name'])
                        $r['implementing_organization_id'] = $org['vincular_con'];

                    if (!empty($org['vincular_con']) && $org['nombre'] == $r['organization_name'])
                        $r['organization_id'] = $org['vincular_con'];
                }
            if (isset($_POST['educacion']))
                foreach ($_POST['educacion'] as $edu)
                    if (!empty($edu['vincular_con']) && $edu['nombre'] == $r['education_name'])
                        $r['education_id'] = $edu['vincular_con'];

            $key = $r['project_code'] . '-' . UString::sustituirEspacios($r['implementing_organization_name']) . '-' . $r['date_entry_project'];
            $eventos[$key]['cabecera'] = ['implementing_organization_id' => $r['implementing_organization_id'], 'country_id' => $_POST['pais']];
            $eventos[$key]['proyectoNuevo'] = (int)$r['project_id'] > 0 ? false : true;
            $eventos[$key]['proyectoId'] = (int)$r['project_id'];
            $eventos[$key]['fechaIngreso'] = $r['date_entry_project'];
            $eventos[$key]['paisNombre'] = $paisNombre;
            $eventos[$key]['proyecto'] = ['code' => $r['project_code'], 'name' => $r['project_name']];
            $eventos[$key]['organizacionNueva'] = (int)$r['implementing_organization_id'] > 0 ? false : true;
            $eventos[$key]['organizacionImplementadora'] = ['name' => $r['implementing_organization_name']];
            if (!isset($eventos[$key]['detalles']))
                $eventos[$key]['detalles'] = [];
            $eventos[$key]['detalles'][] = $r;

        }
    }

    private function BeneficiariosPaso2GuardarEventos($eventos, &$archivo)
    {
        set_time_limit(-1);
        ini_set('memory_limit', -1);
        $eventosCreados = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($eventos as $evento) {
                $id = null;
                if (!Event::CreateFromImport($evento, $id)) {
                    $transaction->commit();
                } else {
                    $eventosCreados[] = $id;
                }
            }
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

    public function actionBeneficiariosPaso3($archivo)
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

    public function actionBeneficiariosPaso4($archivo)
    {
        #try {
        $pathImportJson = Yii::getAlias('@ImportJson/beneficiarios/');
        $resultados = Json::decode(file_get_contents($pathImportJson . $archivo));


        $queryDocumentos = (new Query());
        $queryDocumentos->select('doc_id')
            ->from('sql_debug_contact_doc');
        $documentos = $queryDocumentos->column();

        $queryNombres = (new Query());
        $queryNombres->select('name')
            ->from('sql_debug_contact_name');
        $nombres = $queryNombres->column();

        $queryContact = (new Query());
        $queryContact->select(['contact_id', 'trim(upper(contact_name)) as contact_name', 'contact_sex', 'contact_document', 'contact_organization'])
            ->from('sql_full_report_project_contact')
            ->orWhere(['in', "trim( REPLACE ( REPLACE (contact_document, '-', '' ), ' ', '' ) )", $documentos])
            ->orWhere(['in', "trim(upper(replace(replace(replace(contact_name,' ','<>'),'><',''),'<>',' ')))", $nombres])
            ->andWhere(['in', 'event_id', $resultados])
            ->andWhere('contact_document is not null')
            ->groupBy(['contact_name', "contact_sex", "contact_document", "contact_organization", 'contact_id']);
        $personas = $queryContact->all();
        $data = ['data' => $personas];
        return $this->render('beneficiarios/wizard', ['data' => $data, 'view' => 'step-4', 'stepActive' => 'step4']);
        #} catch (Exception $exception) {
        #    $this->redirect(['import/beneficiarios-paso3', 'archivo' => $archivo]);
        #}

    }


}
