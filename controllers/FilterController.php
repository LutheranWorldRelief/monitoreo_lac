<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Filter;
use app\models\search\Filter as FilterSearch;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DataListController implements the CRUD actions for DataList model.
 */
class FilterController extends Controller
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
        $searchModel = new FilterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere('COALESCE(filter_id, 0) = 0');

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
        if ($model->filter_id)
            $this->redirect(['view', 'id' => $model->filter_id]);

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
        if (($model = Filter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new DataList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Filter();
        $model->start = '0';
        $model->end = '0';
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
            $model->estableceSlug();
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

        $response = Yii::$app->response;

        if (!$model->filter_id) {
            $model->delete();
            return $this->redirect(['index']);
        } else {
            $id = $model->filter_id;
            $model->delete();
            return $this->redirect(['view', 'id' => $id]);

        }
        //        $response->format = Response::FORMAT_JSON;
        //        return ['success' => $model->delete()];
    }

    public function actionSaveDetail($id)
    {
        $model = new Filter();
        $request = Yii::$app->getRequest();
        $response = Yii::$app->response;
        if ($request->isPost && $model->load($request->post())) {
            $model->filter_id = $id;
            if ($model->filter)
                $model->slug = $model->filter->slug;
            $response->format = Response::FORMAT_JSON;
            return ['success' => $model->save(), 'modelo' => $model];
        }

        $response->format = Response::FORMAT_JSON;
        $response->statusCode = 400;
        return ['success' => false, 'errores' => $_POST];
    }

    public function actionUpdateDetail($id)
    {
        $model = $this->findModel($id);
        $filter_id = $model->filter_id;
        $request = Yii::$app->getRequest();
        $response = Yii::$app->response;
        if ($request->isPost && $model->load($request->post())) {
            $model->filter_id = $filter_id;
            $response->format = Response::FORMAT_JSON;
            return ['success' => $model->save()];
        }

        $response->format = Response::FORMAT_JSON;
        $response->statusCode = 400;
        return ['success' => false];
    }

    public function actionValidateDetail()
    {
        $model = new Filter();
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
