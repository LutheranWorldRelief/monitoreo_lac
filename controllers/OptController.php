<?php

namespace app\controllers;

use app\components\ULog;
use app\models\Address;
use app\models\Attendance;
use app\models\AuthUser;
use app\models\Contact;
use app\models\ContactGroups;
use app\models\ContactWorkedWith;
use app\models\DataList;
use app\models\Email;
use app\models\Phonenumber;
use app\models\Project;
use app\models\Organization;
use app\models\ProjectContact;
use app\models\Socialnetwork;
use app\models\SqlContact;
use app\models\Website;
use function array_keys;
use function array_merge;
use function str_replace;
use Yii;
use app\components\Controller;
use app\models\SqlDebugContactName;
use app\models\SqlDebugContactDoc;
use yii\debug\models\search\Log;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Response;

class OptController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    private $removeFields = [
        'created',
        'modified',
        'errors',
    ];

    private $extraFields = [
    ];

    private function baseQuery()
    {
        $user = Yii::$app->user;
        $request = Yii::$app->request;
        $projectId = $request->get('projectId');
        $countryCode = $request->get('countryCode');
        $organizationId = $request->get('organizationId');

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

        if (!$auth->is_superuser)
        {
            $query->andWhere([
                'or',
                ['sql_contact.country' => $auth->countriesArray()],
                ['project.id' => $auth->projectsArray()],
            ]);
        }

        return $query;
    }

    //-------------------------------------------------------------------------- BY ID
    public function actionApiContact($id)
    {
        $model = Contact::findOne($id);
        if (!$model)
            return [];

        $modelsName = $this->namesQuery($model->name)
            ->select('sql_contact.id')
            ->all();

        $modelsDoc  = $this->docsQuery($model->document)
            ->select('sql_contact.id')
            ->all();

        return $this->renderModels(array_merge($modelsName, $modelsDoc));
    }

    //-------------------------------------------------------------------------- BY NAME
    public function actionApiName($name)
    {
        $query = $this->namesQuery($name);

        return $this->renderModels($query->select('sql_contact.id')->all());
    }

    public function actionApiNameValues()
    {
        return $this->renderModelsById(Yii::$app->request->post('ids'));
    }

    private function namesQuery($name=null)
    {
        $query = $this->baseQuery();

        $query
            ->andFilterWhere(['TRIM(sql_contact.name)' => TRIM($name)])
            ->andWhere("NOT TRIM(sql_contact.name) = ''");

        if($name==='')
            $query->andWhere("1 = 0");

        return $query;

    }

    private function namesModels($name=null)
    {
        $query = $this->namesQuery($name);

        $query->select([
                'sql_contact.id as id',
                'TRIM(sql_contact.name) as name',
                'count(DISTINCT sql_contact.id) as cuenta',
            ])
            ->andWhere('TRIM(sql_contact.name) <> ""')
            ->groupBy('sql_contact.name')
            ->having('cuenta > 1');

        return $query->all();
    }

    public function actionApiNames()
    {
        $models = $this->namesModels();

        return $this->renderJson($models);
    }

    //-------------------------------------------------------------------------- BY DOCS
    public function actionApiDoc($doc)
    {
        $query = $this->docsQuery($doc);

        return $this->renderModels($query->select('sql_contact.id')->all());
    }

    public function actionApiDocValues()
    {
        return $this->renderModelsById(Yii::$app->request->post('ids'));
    }

    private function docsQuery($doc=null)
    {
        $query = $this->baseQuery();

        $doc2 = trim(str_replace(" ", "", str_replace("-", "", $doc)));

        $query
            ->andFilterWhere(["TRIM( REPLACE( REPLACE(sql_contact.document, '-', '' ), ' ', '' ) )" => $doc2])
            ->andWhere("NOT TRIM( REPLACE( REPLACE(sql_contact.document, '-', '' ), ' ', '' ) ) = ''");

        if($doc==='')
            $query->andWhere("1 = 0");

        return $query;
    }

    private function docsModels($doc=null)
    {
        $query = $this->docsQuery($doc);

        $query
            ->select([
                'sql_contact.id as id',
                "TRIM( REPLACE( REPLACE(sql_contact.document, '-', '' ), ' ', '' ) ) as document",
                'count(DISTINCT sql_contact.id) as cuenta',
            ])
            ->andWhere('TRIM(IFNULL(sql_contact.document, "")) <> ""')
            ->groupBy('document')
            ->having('cuenta > 1');

        return $query->all();
    }

    public function actionApiDocs()
    {
        $models = $this->docsModels();

        return $this->renderJson($models);
    }

    //-------------------------------------------------------------------------- LISTS/CATALOGS

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

    public function actionApiEmpty()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Contact();

        $data = $model->attributes;

        foreach ($this->removeFields as $field)
            if(isset($data[$field])) unset($data[$field]);

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
            if(isset($data[$field])) unset($data[$field]);

        return $data;
    }

    //-------------------------------------------------------------------------- FUSION
    public function actionApiFusion()
    {
        $request = Yii::$app->request;
        $id = $request->post('id');
        $ids = $request->post('ids');
        $values = $request->post('values');

        $model = null;
        $models = Contact::findAll($ids);

        if(count($models) < 2)
            throw new HttpException(500, "No se cargaron los modelos necesarios para la fusión");

        foreach ($models as $key => $m){
            if ($m->id == $id){
                $model = $m;
                unset($models[$key]);
                break;
            }
        }

        if(!$model)
            throw new HttpException(500, "No se logró identificar al modelo principal de la fusión");

        foreach ($values as $key => $value){
            if ($key == 'id')
                continue;
            $model[$key] = $value;
        }

        $result = [
            'Direcciones'         => [],
            'Participaciones'     => [],
            'Grupos'              => [],
            'Trabaja Con'         => [],
            'Trabaja Para'        => [],
            'Email'               => [],
            'Teléfono'            => [],
            'Proyectos-Contactos' => [],
            'Red Social'          => [],
            'Website'             => [],
            'Eliminado'           => [],
        ];
        $transaction = Yii::$app->db->beginTransaction();
        try{

            $saved = $model->save();

            if (!$saved)
                throw new HttpException(500, "No se logró guardar el modelo principal con los cambios");
            else{
                foreach ($models as $key => $m) {
                    $mid = $m->id;
                    $result['Direcciones'][$mid]        = Address::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Participaciones'][$mid]    = Attendance::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Grupos'][$mid]             = ContactGroups::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Trabaja Con'][$mid]        = ContactWorkedWith::updateAll(['from_contact_id' => $id], ['from_contact_id' => $mid]);
                    $result['Trabaja Para'][$mid]       = ContactWorkedWith::updateAll(['to_contact_id' => $id], ['to_contact_id' => $mid]);
                    $result['Email'][$mid]              = Email::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Teléfono'][$mid]           = Phonenumber::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Proyectos-Contactos'][$mid]            = ProjectContact::updateAll(['contact_id'=>$id], ['contact_id'=>$mid]);
                    $result['Red Social'][$mid]         = Socialnetwork::updateAll(['contact_id' => $id], ['contact_id' => $mid]);
                    $result['Website'][$mid]            = Website::updateAll(['contact_id'=>$id], ['contact_id'=>$mid]);
                    $result['Eliminado'][$mid]          = $m->delete();

                }
            }
            $transaction->commit();
        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw new HttpException(500, $e->getMessage());
        }

        Yii::warning([
            'result'=> $result,
            'errors'=> $model->errors,
        ], 'developer');

        return $this->renderJson([
            'result'=>$result,
            'id'=>$id,
            'model'=>$model,
            'models'=>$models,
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

        foreach ($attributes as $attr => $value)
        {
            if ($attr == 'id') continue;
            if (!isset($resul[$attr]))
                $result[$attr] = [];
            if(!is_array($result[$attr]))
                $result[$attr] = [];

            foreach ($models as $model)
            {
                if ($model[$attr] !== null && trim($model[$attr]) != "" && !in_array($model[$attr], $result[$attr]))
                {
                    $result[$attr][] = $model[$attr];
                }
            }
        }

        $resolve = [];

        foreach($result as $key => $values){
            $count = count($values);
            if($count == 0)
                $result[$key] = null;
            elseif(count($values) == 1)
                $result[$key] = $values[0];
            else
                $resolve[$key] = $values;
        }

        if (!isset($resolve['type_id'])){
            $resolve['type_id'] = array_keys(DataList::itemsBySlug('participantes'));
        }

        Yii::warning([
            'result'  => $result,
            'resolve' => $resolve,
        ], 'developer');

        return $this->renderJson([
            'ids'=>$ids,
            'values'=>$result,
            'resolve'=>$resolve,
        ]);
    }

    private function renderModels($ms)
    {
        $ids = ArrayHelper::map($ms,'id', 'id');

        $models = Contact::findAll($ids);

        return $this->renderJson([
            'models'=>$models,
        ]);
    }
}