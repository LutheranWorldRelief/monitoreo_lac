<?php

namespace app\controllers;

use yii\web\Controller;
use app\components\TranslateStringJs as t;
use yii\web\Response;
use Yii;


class TranslateStringJsController extends Controller
{
    public function actionTranslate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return t::translateJs();
    }

}
