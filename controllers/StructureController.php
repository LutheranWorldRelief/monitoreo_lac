<?php

namespace app\controllers;

use app\models\Structure;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * StructureController implements the CRUD actions for Structure model.
 */
class StructureController extends ControladorController
{

    //  public $enableCsrfValidation = false;
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
     * Creates a new Structure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project, $parent = null)
    {
        $model = new Structure();
        if ($parent)
            $model->structure_id = $parent;
        $model->project_id = $project;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['project/view', 'id' => $model->project_id]);
        } else {
            return $this->render('create', ['model' => $model, 'project' => $project]);
        }
    }

    /**
     * Updates an existing Structure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id, $project)
    {
        $model = $this->findModel($id);
        $model->project_id = $project;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['project/view', 'id' => $model->project_id]);
        } else {
            return $this->render('update', ['model' => $model, 'project' => $project]);
        }
    }

    /**
     * Finds the Structure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Structure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Structure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Deletes an existing Structure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionEliminar($id, $project)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['project/view', 'id' => $project]);
    }

}
