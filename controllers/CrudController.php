<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CrudController extends ControladorController
{

    public $_modelo;
    public $_modelo_search;
    public $_titulo;
    public $_with = [];
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRegistros()
    {
        $clase = $this->_modelo;
        $with = $this->_with;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $clase::find()->with($with)->asArray()->all();
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    protected function findModel($id)
    {
        $class = $this->_modelo;
        if (($model = $class::findOne($id)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $class = $this->_modelo;
        $model = new $class;
        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);
        return $this->render('create', ['model' => $model,]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);
        return $this->render('update', ['model' => $model,]);
    }

    public function actionEliminar()
    {
        $id = Yii::$app->request->post('data');
        $this->findModel($id)->delete();
    }

}
