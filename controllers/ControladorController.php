<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ControladorController extends Controller
{

    public $enableCsrfValidation = false;

        public function validacionPost()
        {
            if (!\Yii::$app->request->isPost)
                throw new \Exception('Acceso No Autorizado a API');
        }
}
