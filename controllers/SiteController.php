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
        $model = new LoginForm();

        /** Validar si existe session en Django **/
        if (isset($_COOKIE['sessionid']))
            $this->setSessionWithDjango($model);

        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', ['model' => $model,]);
    }

    /**
     * Establecer session con datos de usuario logueado en django
    **/
    private function setSessionWithDjango($model)
    {
        $sessionid = $_COOKIE['sessionid'];

        $query = (new Query());
        $query
            ->select(["convert_from(decode(session_data, 'base64'), 'utf-8')"])
            ->from('django_session')
            ->andWhere([
                'session_key' => pg_escape_string($sessionid)
            ]);

        /*Si usuario viene de cerrar session, no loguear nuevamente*/
        if (!isset($_COOKIE['isLogout'])) {

            $sessionDjango = Yii::$app->db2->createCommand($query->createCommand()->getRawSql())->queryOne();

           if(!$sessionDjango)
            goto breakSearch;

            list($hash, $json) = preg_split("/:/", $sessionDjango['convert_from'], 2);
            $data = json_decode($json, true);

            $queryGetUser = (new Query());
            $queryGetUser
                ->select(["username", "email"])
                ->from('auth_user')
                ->andWhere([
                    'id' => $data['_auth_user_id']
                ]);

            $authUser = Yii::$app->db2->createCommand($queryGetUser->createCommand()->getRawSql())->queryOne();

            if(!$authUser)
                goto breakSearch;

            if ($model->login($authUser['username']))
                return $this->goBack();

            breakSearch: return false;

        }
        $this->removeIsLogout();

    }

    public function removeIsLogout()
    {
        setcookie("isLogout", null, time()-3700);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        setcookie('isLogout', 1 );

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
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Contraseña cambiada con éxito'));
                    } else
                        Yii::$app->session->setFlash('error', Yii::t('app', 'La nueva clave no coincide con el campo de confirmación'));
                } else
                    Yii::$app->session->setFlash('error', Yii::t('Contraseña actual inválida'));
            } else
                Yii::$app->session->setFlash('error', Yii::t('Debe ingresar la contraseña actual y la nueva'));

        }

        if ($user->load(Yii::$app->request->post())) {
            $user->password = 'nestic';
            if ($user->modificar($pass))
                Yii::$app->session->setFlash('success', Yii::t('app', 'Perfil actualizado con éxito'));
            else
                Yii::$app->session->setFlash('error', Yii::t('app', 'No se logró actualizar su perfil'));
        }
        return $this->render('profile', ['user' => $user]);
    }

    public function actionAutologin()
    {
        if (!isset($_COOKIE['sessionid'])) {
            return $this->goHome();
        }
        $sessionid =  $_COOKIE['sessionid'];
        $dbname = Yii::$app->components['db']['username'];
        $db = pg_connect("dbname=$dbname user=$dbname");
        $res = pg_query($db, "SELECT convert_from(decode(session_data, 'base64'), 'utf-8') FROM django_session WHERE session_key='" . pg_escape_string($sessionid) . "'");
        $val = pg_fetch_result($res, 0);
        list($hash, $json) = preg_split("/:/", $val, 2);
        $data = json_decode($json, true);
        $userid = $data['_auth_user_id'];
        $res = pg_query($db, "SELECT username, email FROM auth_user WHERE id = " . $userid );
        $username = pg_fetch_result($res, 0);
        $identity = \app\models\AuthUser::findByUsername($username);
        if (Yii::$app->user->login($identity)) {
            return $this->goHome();
        } else {
            throw new NotFoundHttpException();
        }

    }
}
