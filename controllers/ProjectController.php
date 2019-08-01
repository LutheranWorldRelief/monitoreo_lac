<?php

namespace app\controllers;

use app\models\Contact;
use app\models\Project;
use app\models\ProjectContact;
use app\models\search\SqlProject as ProjectSearch;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\db\Direction;
use function count;
use const SORT_ASC;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends ControladorController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'api-contact' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        $this->guardar($model);
        return $this->render('create', ['model' => $model,]);
    }

    private function guardar($model)
    {
        $logo = $model->logo;
        if ($model->load(Yii::$app->request->post())) {
            $model->logo = UploadedFile::getInstance($model, 'logo');
            $model->SubirLogo();
            if (!$model->logo)
                $model->logo = $logo;
            if ($model->save())
                return $this->redirect(['index']);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->guardar($model);
        return $this->render('update', ['model' => $model,]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDataStructure()
    {
        $request = Yii::$app->request;
        $query = (new Query());
        $query
            ->select(['e.*', 'project' => 'p.name'])
            ->from('structure e')
            ->leftJoin('project p', 'e.project_id = p.id')
            ->andFilterWhere(['project_id' => $request->get('proyecto')])
            ->orderBy([new \yii\db\Expression('structure_id ASC NULLS FIRST'), 'code' => SORT_ASC, 'description' => SORT_ASC]);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $query->all();
    }

    public function actionApiContact($projectId, $contactId)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $response = Yii::$app->response;

        //        $project = $this->findModel($projectId);
        //        $contact = $this->findModelContact($contactId);
        $projectContact = $this->findModelProjectContact($projectId, $contactId);
        if (!$projectContact)
            $projectContact = new ProjectContact();
        $data = $request->post();

        if ($projectContact->load($data)) {
            $projectContact->contact_id = $contactId;
            $projectContact->project_id = $projectId;

            if (!$projectContact->save()) {
                $response->statusCode = 400;
                $response->statusText = 'error_registro_dato';
            }
        }

        return [
            'projectContact' => $projectContact,
        ];

    }

    /**
     * Finds the ProjectContact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return ProjectContact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelProjectContact($projectId, $contactId)
    {
        return ProjectContact::find()
            ->andWhere([
                'project_id' => $projectId,
                'contact_id' => $contactId
            ])
            ->one();
    }

    public function actionApiContacts($projectId, $q = null)
    {

        $project = $this->findModel($projectId);

        $contactIds = ArrayHelper::map((new Query())
            ->select(['contact_id'])
            ->from('sql_attendance_project_contact')
            ->andWhere(['project_id' => $projectId])
            ->groupBy('contact_id')
            ->all(), 'contact_id', 'contact_id');

        $query = Contact::find()
            ->select(['id', 'name'])
            ->with(['projectContactOne' => function ($query) use ($projectId) {
                $query->andWhere(['project_id' => $projectId]);
            }])
            ->andWhere(['id' => $contactIds])
            ->orderBy(['TRIM(name)' => SORT_ASC]);

        if ($q)
            $query->andWhere(['or', ['id' => $q], ['like', 'name', trim($q)]]);


        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'q' => $q,
            'projectId' => $projectId,
            'project' => $project,
            'count' => [
                'contacts' => $query->count() * 1,
                'contactsIds' => count($contactIds),
            ],
            'labels' => [
                'contact' => (new Contact())->attributeLabels(),
                'projectContact' => (new ProjectContact())->attributeLabels(),
            ],
            'new' => [
                'contact' => new Contact(),
                'projectContact' => new ProjectContact(),
            ],
            'models' => $query->asArray()->all(),
        ];

    }

    public function actionContacts($projectId)
    {

        $project = $this->findModel($projectId);

        return $this->render('contacts', [
            'project' => $project,
        ]);

    }

    /**
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelContact($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
