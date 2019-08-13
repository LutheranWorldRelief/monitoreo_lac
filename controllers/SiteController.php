<?php

namespace app\controllers;

use app\components\Excel;
use app\models\ContactForm;
use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\db\Query;

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
        //print_r($_POST);
        $_POST=null;
        $_POST['LoginForm'] = ['username' => 'admin', 'password' => 'agua123', 'rememberMe' => 1] ;
        $_POST['login-button'] = null;


        /** INICIO @var   $cookies */
        $cookies = Yii::$app->request->cookies;
        if(Yii::$app->getRequest()->getCookies()->has('sessionid'))
        {
            $sessionid = $cookies->get('sessionid'); //'zwr599hsf9n8efk0a2n6b3tfq4uj3vw8';

          /*  $res = pg_query($db, "SELECT convert_from(decode(session_data, 'base64'), 'utf-8')
            FROM django_session WHERE session_key='" . pg_escape_string($sessionid) . "'");*/

       /*   Yii::$app->db2;
            $query = (new Query());
            $query
                ->select(["convert_from(decode(session_data, 'base64'), 'utf-8')"])
                ->from('django_session')
                ->andWhere([
                    'session_key' => pg_escape_string($sessionid)
                ]);*/
            $sessionDjango = Yii::$app->db2->createCommand(" SELECT convert_from(decode(session_data, 'base64'), 'utf-8') FROM 
	                          django_session  WHERE session_key='".pg_escape_string($sessionid)."'"
                              )->queryOne();

            list($hash, $json) = preg_split("/:/", $sessionDjango['convert_from'], 2);
            $data = json_decode($json, true);

            $authUser = Yii::$app->db2->createCommand("SELECT username, email FROM auth_user WHERE id = " . $data['_auth_user_id'])->queryOne();

            print_r($authUser['username']); exit();

              /*  ->andFilterWhere(['project_id' => $request->get('proyecto')])
                ->orderBy([new \yii\db\Expression('structure_id ASC NULLS FIRST'), 'code' => SORT_ASC, 'description' => SORT_ASC]);
                */

          /*  Yii::$app->response->format = Response::FORMAT_JSON;
            return $query->all();*/

        }

         /* FIN */
        
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
            if (!empty($passwordPost['new']) && !empty($passwordPost['current'])) {
                if ($user->validatePassword($passwordPost['current'])) {
                    if (($passwordPost['new'] === $passwordPost['confirm'])) {
                        $user->password = $passwordPost['new'];
                        $user->modificar($pass);
                        Yii::$app->session->setFlash(Yii::t('app', 'success', "Contraseña cambiada con éxito"));
                    } else
                        Yii::$app->session->setFlash(Yii::t('app', 'error', "La nueva clave no coincide con el campo de confirmación"));
                } else
                    Yii::$app->session->setFlash(Yii::t('app', 'error', "Contraseña actual inválida"));
            } else
                Yii::$app->session->setFlash(Yii::t('app', 'error', "Debe ingresar la contraseña actual y la nueva"));

        }

        if ($user->load(Yii::$app->request->post())) {
            $user->password = 'nestic';
            if ($user->modificar($pass))
                Yii::$app->session->setFlash(Yii::t('app', 'success', "Perfil actualizado con éxito"));
            else
                Yii::$app->session->setFlash(Yii::t('app', 'error', "No se logró actualizar su perfil"));
        }
        return $this->render('profile', ['user' => $user]);
    }
}
