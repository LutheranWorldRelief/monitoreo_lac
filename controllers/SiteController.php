<?php

namespace app\controllers;

use app\components\Excel;
use app\models\AuthUser;
use app\models\Contact;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\Ulog;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return Yii::$app->getResponse()->redirect('graphic/dashboard');
//        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', ['model' => $model,]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', ['model' => $model,]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionProfile()
    {

        if (Yii::$app->user->isGuest)
            $this->redirect('login');

        $user = Yii::$app->user->identity;
        $pass = $user->password;

        $passwordPost = Yii::$app->request->post('Password');

        if ($passwordPost) {
            if (!empty($passwordPost['new']) &&!empty($passwordPost['current'])) {
                if ($user->validatePassword($passwordPost['current'])) {
                    if (($passwordPost['new'] === $passwordPost['confirm'])) {
                        $user->password = $passwordPost['new'];
                        $user->modificar($pass);
                        Yii::$app->session->setFlash('success', "Contraseña cambiada con éxito");
                    } else
                        Yii::$app->session->setFlash('error', "La nueva clave no coincide con el campo de confirmación");
                } else
                    Yii::$app->session->setFlash('error', "Contraseña actual inválida");
            } else
                Yii::$app->session->setFlash('error', "Debe ingresar la contraseña actual y la nueva");

        }

        if($user->load(Yii::$app->request->post())){
            $user->password = 'nestic';
            if($user->modificar($pass))
                Yii::$app->session->setFlash('success', "Perfil actualizado con éxito");
            else
                Yii::$app->session->setFlash('error', "No se logró actualizar su perfil");
        }
        return $this->render('profile', ['user' => $user]);
    }
}
