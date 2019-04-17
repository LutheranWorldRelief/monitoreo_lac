<?php

namespace app\controllers;

use app\models\AuthUser;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ControladorRestController extends ActiveController
{

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['login'] //action that you don't want to authenticate such as login
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $user = AuthUser::findByUsername($request->post('usuario'));
        if ($user && $user->validatePassword($request->post('clave'))) {
            $user->generarToken();
            return $user->access_token;
        }
        return false;
    }

}
