<?php

namespace app\components;

use Yii;
use yii\web\Response;

abstract class Controller extends \yii\web\Controller {

    protected function renderJson($data){

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $data;
    }

}
