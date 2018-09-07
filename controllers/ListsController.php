<?php

namespace app\controllers;

use Yii;
use app\components\Controller;
use yii\bootstrap\ActiveForm;
use app\components\Ulog;
use app\models\DataList;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
USE yii\web\Response;

class ListsController extends Controller
{
	// ------------------------------------------------------------ INDEX
    public function actionIndex()
    {
    	$search = new DataList();
        $provider = $search->search(Yii::$app->request->queryParams);
 
        return $this->render('index', [
            'search' => $search,
            'provider' => $provider,
        ]);
    }

	// ------------------------------------------------------------ VIEW
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	$list = $model->dataLists;

        return $this->render('view', [
            'model' => $model,
            'provider' => new ArrayDataProvider([
			    'allModels' => $list,
			    'key'=>'id',
			    'pagination' => false
			])
        ]);
    }

	// ------------------------------------------------------------ DELETE
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
 
        $this->redirect(['index']);
    }

	// ------------------------------------------------------------ CREATE
    public function actionCreate()
    {
    	return $this->form(new DataList());
    }

	// ------------------------------------------------------------ UPDATE
    public function actionUpdate($id)
    {
    	return $this->form($this->findModel($id));
    }

	// ------------------------------------------------------------ FORM
    private function form($model)
    {
        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);

        return $this->render('form', [
            'model' => $model,
        ]);
    }

	// ------------------------------------------------------------ FORM
    public function actionNewDetail($id)
    {
    	$parent = $this->findModel($id);
    	$model = new DataList;
	    $response = [];

        if ($model->load(Yii::$app->request->post())){
        	$model->list_id = $id;
        	if($model->save())
	    		Yii::$app->response->format = Response::FORMAT_JSON;
            	return ['success' => $model->save()];
        }        
        
	    Yii::$app->response->format = Response::FORMAT_JSON;
	    return ActiveForm::validate($model);
    }

	// ------------------------------------------------------------ FORM
    public function actionValidate()
    {
    	$model = new DataList;
    	$model->load(Yii::$app->request->post());
    	if (Yii::$app->request->isAjax) {
		    Yii::$app->response->format = Response::FORMAT_JSON;
		    return ActiveForm::validate($model);
		}
    }

	// ------------------------------------------------------------ FIND
    protected function findModel($id)
    {
        if (($model = DataList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}