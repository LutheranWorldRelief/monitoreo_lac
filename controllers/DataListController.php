<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Contact;
use app\models\DataList;
use app\models\search\DataList as DataListSearch;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DataListController implements the CRUD actions for DataList model.
 */
class DataListController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DataList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DataListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere('COALESCE(data_list_id, 0) = 0');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataList model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
//        var_dump($model);
        if ($model->data_list_id)
            $this->redirect(['view', 'id' => $model->data_list_id]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the DataList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return DataList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Displays a single DataList model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionViewContacts($id)
    {
        $model = $this->findModel($id);

        $contacts = Contact::find()
            ->orWhere([
                'and',
                ['!=', 'education_id', null],
                ['education_id' => $id],
            ])
            ->orWhere([
                'and',
                ['!=', 'type_id', null],
                ['type_id' => $id],
            ])
            ->orWhere([
                'and',
                ['!=', 'country_id', ''],
                ['country_id' => $model->value],
            ])
            ->orderBy('name')
            ->all();

        return $this->render('view_contacts', [
            'model' => $model,
            'contacts' => $contacts,
        ]);
    }

    /**
     * Creates a new DataList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DataList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DataList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DataList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $padre = (int)$model->data_list_id;
        $contactsCount = Contact::find()
            ->orWhere([
                'and',
                ['!=', 'education_id',(int) ''],
                ['education_id' =>(int) $id],
            ])
            ->orWhere([
                'and',
                ['!=', 'type_id',(int) ''],
                ['type_id' =>(int) $id],
            ])
            ->orWhere([
                'and',
                ['!=', 'country_id', ''],
                ['country_id' => $model->value],
            ])
            ->count();
        if ($contactsCount > 0)
            throw new HttpException(403, 'Aún hay registros relacionados con este item. No puede ser eliminado.');

        $model->delete();
        return $padre > 0 ? $this->redirect(['data-list/view', 'id' => $padre]) : $this->redirect(['data-list/index']);
    }

    public function actionSaveDetail($id)
    {
        $model = new DataList();
        $request = Yii::$app->getRequest();
        $response = Yii::$app->response;
        if ($request->isPost && $model->load($request->post())) {
            $model->data_list_id = $id;
            $response->format = Response::FORMAT_JSON;
            $model->validate();
            return ['success' => $model->save(), 'errors' => $model->getFirstErrors()];
        }

        $response->format = Response::FORMAT_JSON;
        $response->statusCode = 400;
        return ['success' => false];
    }

    public function actionUpdateDetail($id)
    {
        $model = $this->findModel($id);
        $list_id = $model->data_list_id;
        $request = Yii::$app->getRequest();
        $response = Yii::$app->response;
        if ($request->isPost && $model->load($request->post())) {
            $model->data_list_id = $list_id;
            $response->format = Response::FORMAT_JSON;
            $model->validate();
            return ['success' => $model->save(), 'errors' => $model->getFirstErrors()];
        }

        $response->format = Response::FORMAT_JSON;
        $response->statusCode = 400;
        return ['success' => false];
    }

    public function actionValidateDetail()
    {
        $model = new DataList();
        $request = Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionFind($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->findModel($id);
    }
}
