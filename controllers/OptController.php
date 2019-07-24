<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Attendance;
use app\models\AuthUser;
use app\models\Contact;
use app\models\DataList;
use app\models\Organization;
use app\models\Project;
use app\models\ProjectContact;
use app\models\SqlContact;
use app\models\SqlFullReportProjectContact;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Response;
use function array_merge;
use function str_replace;

class OptController extends Controller
{
    private $removeFields = [
        'created',
        'modified',
        'errors',
    ];
    private $extraFields = [
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionApiContact($id)
    {
        $model = Contact::findOne($id);
        if (!$model)
            return [];

        Yii::warning($model->attributes, 'developer');

        $modelsName = $this->namesQuery($model->name)
            ->select('sql_contact.id')
            ->all();

        $modelsDoc = $this->docsQuery($model->document)
            ->select('sql_contact.id')
            ->all();

        return $this->renderModels(array_merge($modelsName, $modelsDoc));
    }

    //-------------------------------------------------------------------------- BY ID

    private function namesQuery($name = null)
    {
        $query = $this->baseQuery();

        $name2 = preg_replace('/\s+/', " ", TRIM($name));
        if ($name2)
            $query->andWhere("REGEXP_REPLACE(TRIM(sql_contact.name), '( ){2,}', ' ') = $name2");

        $query->andWhere("NOT TRIM(sql_contact.name) = ''");

        if ($name === '')
            $query->andWhere("1 = 0");

        return $query;

    }

    //-------------------------------------------------------------------------- BY NAME

    private function baseQuery()
    {
        $user = Yii::$app->user;
        $request = Yii::$app->request;
        $projectId = $request->get('projectId');
        $countryCode = $request->get('countryCode');
        $organizationId = $request->get('organizationId');
        $nameSearch = $request->get('nameSearch');

        /* @var AuthUser $auth */
        $auth = $user->identity;

        $query = SqlContact::find()
            ->leftJoin('attendance', 'attendance.contact_id = sql_contact.id')
            ->leftJoin('event', 'event.id = attendance.event_id')
            ->leftJoin('structure', 'structure.id = event.structure_id')
            ->leftJoin('project', 'project.id = structure.project_id');

        $query
            ->andFilterWhere(['project.id' => $projectId])
            ->andFilterWhere(['sql_contact.country' => $countryCode])
            ->andFilterWhere(['event.implementing_organization_id' => $organizationId]);


        if (!empty($nameSearch))
            $query
                ->andFilterWhere(['like', 'sql_contact.name', $nameSearch]);

        if (!$auth->is_superuser) {
            $query->andWhere([
                'or',
                ['sql_contact.country' => $auth->countriesArray()],
                ['project.id' => $auth->projectsArray()],
            ]);
        }

        return $query;
    }

    private function docsQuery($doc = null)
    {
        $query = $this->baseQuery();

        $doc2 = preg_replace('/\s+/', ' ', trim(str_replace(" ", "", str_replace("-", "", $doc))));

        $query
            ->andFilterWhere(["TRIM( REPLACE( REPLACE(sql_contact.document, '-', '' ), ' ', '' ) )" => $doc2])
            ->andWhere("NOT TRIM( REPLACE( REPLACE(sql_contact.document, '-', '' ), ' ', '' ) ) = ''");

        if ($doc === '')
            $query->andWhere("1 = 0");

        return $query;
    }

    private function renderModels($ms)
    {
        $ids = ArrayHelper::map($ms, 'id', 'id');

        $models = Contact::findAll($ids);

        return $this->renderJson([
            'models' => $models,
        ]);
    }

    public function actionApiName($name)
    {
        Yii::warning($name, 'developer');

        $query = $this->namesQuery($name);

        return $this->renderModels($query->select('sql_contact.id')->all());
    }

    public function actionApiNameValues()
    {
        return $this->renderModelsById(Yii::$app->request->post('ids'));
    }

    //-------------------------------------------------------------------------- BY DOCS

    private function renderModelsById($ids)
    {
        $models = Contact::findAll($ids);

        $modelMerge = new Contact();
        $attributes = $modelMerge->attributes;

        foreach ($this->removeFields as $field)
            unset($attributes[$field]);

        foreach ($this->extraFields as $field)
            $attributes[$field] = null;

        $result = [];

        foreach ($attributes as $attr => $value) {
            if ($attr == 'id') continue;
            if (!isset($resul[$attr]))
                $result[$attr] = [];
            if (!is_array($result[$attr]))
                $result[$attr] = [];

            foreach ($models as $model) {
                if ($model[$attr] !== null && trim($model[$attr]) != "" && !in_array($model[$attr], $result[$attr])) {
                    $result[$attr][] = $model[$attr];
                }
            }
        }

        $resolve = [];

        foreach ($result as $key => $values) {
            $count = count($values);
            if ($count == 0)
                $result[$key] = null;
            elseif (count($values) == 1)
                $result[$key] = $values[0];
            else
                $resolve[$key] = $values;
        }

        Yii::warning([
            'result' => $result,
            'resolve' => $resolve,
        ], 'developer');

        return $this->renderJson([
            'ids' => $ids,
            'values' => $result,
            'resolve' => $resolve,
        ]);
    }

    public function actionApiNames()
    {
        $models = $this->namesModels();

        return $this->renderJson($models);
    }

    private function namesModels($name = null)
    {
        $query = $this->namesQuery($name);
        //ULog::l($query->createCommand()->query());

        $query->select([
            'sql_contact.id as id',
            'TRIM(sql_contact.name) as name',
            'count(DISTINCT sql_contact.id) as cuenta',
        ])
            ->andWhere("TRIM(sql_contact.name) <> ''")
            ->groupBy(['sql_contact.name', 'sql_contact.id'])
            ->having('count(DISTINCT sql_contact.id) > 1');

        return $query->all();
    }

    public function actionApiDoc($doc)
    {
        $query = $this->docsQuery($doc);

        return $this->renderModels($query->select('sql_contact.id')->all());
    }

    public function actionApiDocValues()
    {
        return $this->renderModelsById(Yii::$app->request->post('ids'));
    }

    //-------------------------------------------------------------------------- LISTS/CATALOGS

    public function actionApiDocs()
    {
        $models = $this->docsModels();

        return $this->renderJson($models);
    }

    private function docsModels($doc = null)
    {
        $query = $this->docsQuery($doc);

        $query
            ->select([
                'sql_contact.id as id',
                "STRING_AGG(DISTINCT  sql_contact.name, '<br>') as name",
                "TRIM( REPLACE( REPLACE(sql_contact.document, '-', '' ), ' ', '' ) ) as document",
                'count(DISTINCT sql_contact.id) as cuenta',
            ])
            ->andWhere("TRIM(COALESCE(sql_contact.document, '')) <> ''")
            ->groupBy(['sql_contact.document', "sql_contact.id"])
            ->having('count(DISTINCT sql_contact.id) > 1');

        return $query->all();
    }

    public function actionApiProjects()
    {
        $data = Project::listData('name');
        return $this->renderJson($data);
    }

    public function actionApiCountries()
    {
        $data = DataList::itemsBySlug('countries', 'name', 'value');
        return $this->renderJson($data);
    }

    public function actionApiEducation()
    {
        $data = DataList::itemsBySlug('education');
        return $this->renderJson($data);
    }

    public function actionApiTypes()
    {
        $data = DataList::itemsBySlug('participantes');
        return $this->renderJson($data);
    }

    public function actionApiOrganizations()
    {
        $data = Organization::listData('name');
        return $this->renderJson($data);
    }

    //-------------------------------------------------------------------------- FUSION

    public function actionApiEmpty()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Contact();

        $data = $model->attributes;

        foreach ($this->removeFields as $field)
            if (isset($data[$field])) unset($data[$field]);

        foreach ($this->extraFields as $field)
            $data[$field] = $model->{$field};

        return $data;
    }

    public function actionApiLabels()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Contact();

        $data = $model->attributeLabels();

        foreach ($this->removeFields as $field)
            if (isset($data[$field])) unset($data[$field]);

        return $data;
    }

    public function actionApiFusion()
    {
        $request = Yii::$app->request;
        $id = $request->post('id');
        $ids = $request->post('ids');
        $values = $request->post('values');

        $model = null;
        $models = Contact::findAll($ids);

        if (count($models) < 2)
            throw new HttpException(500, "No se cargaron los modelos necesarios para la fusión");

        foreach ($models as $key => $m) {
            if ($m->id == $id) {
                $model = $m;
                unset($models[$key]);
                break;
            }
        }

        if (!$model)
            throw new HttpException(500, "No se logró identificar al modelo principal de la fusión");

        foreach ($values as $key => $value) {
            if ($key == 'id')
                continue;
            $model[$key] = $value;
        }

        $model->name = preg_replace('/\s+/', ' ', TRIM($model->name));
        $model->first_name = preg_replace('/\s+/', ' ', TRIM($model->first_name));
        $model->last_name = preg_replace('/\s+/', ' ', TRIM($model->last_name));

        $result = [
            'Direcciones' => [],
            'Participaciones' => [],
            'Grupos' => [],
            'Trabaja Con' => [],
            'Trabaja Para' => [],
            'Email' => [],
            'Teléfono' => [],
            'Proyectos-Contactos' => [],
            'Red Social' => [],
            'Website' => [],
            'Eliminado' => [],
        ];

        $saved = false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->type_id || $model->type_id == '0')
                $model->type_id = null;

            $saved = $model->save();

            if (!$saved)
                throw new HttpException(500, "No se logró guardar el modelo principal con los cambios");
            else {
                foreach ($models as $key => $m) {
                    $mid = $m->id;
                    $result['Participaciones'][$mid] = Attendance::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Proyectos-Contactos'][$mid] = ProjectContact::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Eliminado'][$mid] = $m->delete();

                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new HttpException(500, $e->getMessage());
        }

        Yii::warning([
            'result' => $result,
            'errors' => $model->errors,
        ], 'developer');

        return $this->renderJson([
            'save' => $saved,
            'result' => $result,
            'id' => $id,
            'model' => $model,
            'models' => $models,
        ]);
    }

    public function actionDebugContactDoc()
    {
        $user = Yii::$app->user;
        $request = Yii::$app->request;
        $projectId = $request->post('projectId');
        $countryCode = $request->post('countryCode');
        $organizationId = $request->post('organizationId');

        /* @var AuthUser $auth */
        $auth = $user->identity;
        $projects = $auth->projectsList();
        $countries = $auth->countriesList();
        $organizations = ArrayHelper::map(Organization::find()
            ->select(['id', 'name'])
            ->where(['is_implementer' => true])
            ->orderBy('name')
            ->all(), 'id', 'name');

        return $this->render('debug_contact_doc', [
            'projects' => $projects,
            'organizations' => $organizations,
            'countries' => $countries,
            'countryCode' => $countryCode,
            'projectId' => $projectId,
            'organizationId' => $organizationId,
        ]);
    }

    public function actionDebugContactName()
    {
        $user = Yii::$app->user;
        $request = Yii::$app->request;
        $projectId = $request->post('projectId');
        $countryCode = $request->post('countryCode');
        $organizationId = $request->post('organizationId');

        /* @var AuthUser $auth */
        $auth = $user->identity;
        $projects = $auth->projectsList();
        $countries = $auth->countriesList();
        $organizations = ArrayHelper::map(Organization::find()
            ->select(['id', 'name'])
            ->where(['is_implementer' => true])
            ->orderBy('name')
            ->all(), 'id', 'name');


        return $this->render('debug_contact_name', [
            'projects' => $projects,
            'organizations' => $organizations,
            'countries' => $countries,
            'countryCode' => $countryCode,
            'projectId' => $projectId,
            'organizationId' => $organizationId,
        ]);
    }

    public function actionRemoveContactNoProject()
    {

        $idsWithProjects = ArrayHelper::map(SqlFullReportProjectContact::find()
            ->select('contact_id')
            ->where('NOT ISNULL(project_id) AND NOT ISNULL(contact_id)')
            ->groupBy('contact_id')
            ->asArray()
            ->all(), 'contact_id', 'contact_id');


        $idsWithNoProjects = ArrayHelper::map(Contact::find()
            ->select('id')
            ->where(['not in', 'id', $idsWithProjects])
            ->asArray()
            ->all(), 'id', 'id');


        Yii::$app->response->format = 'json';
        return [
            'projects' => count($idsWithProjects),
            'projects_no' => count($idsWithNoProjects),
            //$idsWithProjects,
            //$idsWithNoProjects,
            Attendance::deleteAll(['contact_id' => $idsWithNoProjects]),
            Contact::deleteAll(['id' => $idsWithNoProjects])
        ];
    }
}
