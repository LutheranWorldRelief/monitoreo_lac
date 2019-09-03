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
        $strTranslated = [];

        foreach (t::translateJs() as $str) {
            $strTranslated[$str]= $str;
        }

        return [ /**<<Inicio dashboard_index.js >>**/
            'mapTitle' =>Yii::t('app', 'Geographic location of participants'),
            'mapTitle2' =>Yii::t('app', '5'),
            /**<< Fin dashboard_index.js >>**/
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return t::translateJs();
    }


}
