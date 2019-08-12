<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Event;
use app\models\search\SqlEvent as EventSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
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
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        $search = new EventSearch();
        $provider = $search->search(Yii::$app->request->queryParams);

        return $this->render('index', ['search' => $search, 'provider' => $provider,]);
    }

    /**
     * Displays a single Event model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::find()->where(['id' => $id])->with('attendances')->one()) !== null)
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();
        $model->start = date('Y-m-d');
        $model->end = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);
        else
            return $this->render('create', ['model' => $model,]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();

        if ($model->load($post))
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);

        return $this->render('update', ['model' => $model,]);
    }

    /**
     * Deletes an existing Event model.
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

}
