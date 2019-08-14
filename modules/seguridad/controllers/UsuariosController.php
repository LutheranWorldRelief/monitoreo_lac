<?php

namespace app\modules\seguridad\controllers;

use app\models\AuthUser;
use app\models\search\SqlUsuario;
use mdm\admin\controllers\AssignmentController;
use mdm\admin\models\Assignment;
use Yii;
use yii\base\ExitException;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class UsuariosController extends AssignmentController
{

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SqlUsuario();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $usuario = AuthUser::findOne($id);

        $activeUser = true;
        $activePais = false;
        $activeProject = false;
        if (isset($_POST['AuthUser']['projects'])) {
            $usuario->projects = $_POST['AuthUser']['projects'];
            $usuario->save();
            $activeProject = true;
            $activeUser = false;
        }
        if (isset($_POST['AuthUser']['countries'])) {
            $usuario->countries = $_POST['AuthUser']['countries'];
            $usuario->save();
            $activePais = true;
            $activeUser = false;
        }

        return $this->render('view', [
            'model' => $model,
            'usuario' => $usuario,
            'activo' => [
                'activePais' => $activePais,
                'activeUser' => $activeUser,
                'activeProject' => $activeProject
            ]
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($user = AuthUser::findIdentity($id)) !== null) {
            return new Assignment($id, $user);
        } else {
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }
    }

    /**
     * If "dektrium/yii2-rbac" extension is installed, this page displays form
     * where user can assign multiple auth items to user.
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAssignments($id)
    {
        Url::remember('', 'actions-redirect');
        return $this->render('_assignments', ['user' => $this->findModel($id),]);
    }

    /**
     * Confirms the User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function actionConfirm($id)
    {
        $this->findModel($id)->confirm();
        Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been confirmed'));
        return $this->redirect(Url::previous('actions-redirect'));
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'No puedes eliminar tu propia cuenta'));
        } else {
            AuthUser::findOne($id)->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Usuario Eliminado'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Creates a new AuthUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthUser();
        if ($model->load(Yii::$app->request->post()) && $model->create())
            return $this->redirect(['view', 'id' => $model->id]);
        else
            return $this->render('create', ['model' => $model,]);
    }

    /**
     * Updates an existing AuthUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = AuthUser::findOne($id);
        $pass = $model->password;
        $model->password = 'nestic';
        if ($model->load(Yii::$app->request->post()) && $model->modificar($pass))
            return $this->redirect(['view', 'id' => $model->id]);
        else
            return $this->render('update', ['model' => $model]);
    }

    /**
     * Performs AJAX validation.
     *
     * @param array|Model $model
     *
     * @throws ExitException
     */
    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                echo json_encode(ActiveForm::validate($model));
                Yii::$app->end();
            }
        }
    }

}
