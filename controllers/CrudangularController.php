<?php

namespace app\controllers;

use Exception;
use Yii;
use yii\web\Response;

class CrudangularController extends ControladorController
{

    public $_modelo;
    public $_catalogo_simple = true;
    public $_titulo;
    public $enableCsrfValidation = false;
    public $_with = [];
    public $_view = '@app/views/compartidas/crudangular/index';

    public function actionIndex()
    {
        //        \app\components\ULog::Log($this->_view);
        if ($this->_catalogo_simple)
            return $this->render($this->_view, ['titulo' => $this->_titulo, 'modeloClass' => $this->_modelo]);
        else
            return $this->render('index', ['modeloClass' => $this->_modelo]);
    }

    public function actionNuevo()
    {
        $clase = $this->_modelo;
        $data = Yii::$app->request->post('data');
        $model = new $clase;
        $model->attributes = $data;
        $result = [];
        if ($model->save()) {
            $result['estado'] = 'ok';
        } else {
            $result['estado'] = 'fail';
            $result['data'] = $model->attributes;
            $result['errores'] = $model->getFirstErrors();
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionRegistros()
    {
        $clase = $this->_modelo;
        $with = $this->_with;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $clase::find()->with($with)->asArray()->all();
    }

    public function actionActualizar()
    {
        $clase = $this->_modelo;
        $data = Yii::$app->request->post('data');
        $model = $this->findModel($data['id']);
        if ($model) {
            $model->attributes = $data;
            $result = [];
            if ($model->save()) {
                $result['estado'] = 'ok';
            } else {
                $result['estado'] = 'fail';
                $result['data'] = $model->attributes;
                $result['errores'] = $model->getFirstErrors();
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            throw new Exception(Yii::t('app','No encontrado, 500'));
        }
    }

    protected function findModel($id)
    {
        $clase = $this->_modelo;
        if (($model = $clase::findOne($id)) !== null)
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
    }

    public function actionEliminar()
    {
        $id = Yii::$app->request->post('data');
        $this->findModel($id)->delete();
    }

}
