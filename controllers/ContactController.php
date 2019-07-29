<?php

namespace app\controllers;

use app\components\Controller;
use app\components\UString;
use app\models\Contact;
use app\models\search\Contact as ContactSearch;
use app\models\search\SqlContactEvent;
use app\models\SqlContact;
use Yii;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionFindAll($q)
    {


        $query = SqlContact::find()->where(['like', 'name', new Expression("UPPER('%" . trim($q) . "%')")])->limit(10)->all();
        foreach ($query as $model)
            $model->name = UString::upperCase(UString::quitarAcentos($model->name));

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $query;
    }

    public function actionFindDoc($q)
    {
        $query = SqlContact::find()->where(['like', 'document', new Expression("UPPER('%" . trim($q) . "%')")])->limit(10)->all();
        $result = [];
        foreach ($query as $model)

            $result[] = [
                'id' => $model->id,
                'name' => $model->document,
                'model' => $model
            ];


        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Check if there is an Editable ajax request
        if (isset($_POST['hasEditable'])) {
            // use Yii's response format to encode output as JSON
            Yii::$app->response->format = Response::FORMAT_JSON;

            $request = Yii::$app->request;

            $model = Contact::findOne($request->post('editableKey'));

            $posted = current($_POST['Contact']);
            $post = ['Contact' => $posted];

            // read your posted model attributes
            if ($model->load($post)) {
                // read or convert your posted information
                $attribute = $request->post('editableAttribute');
                if ($attribute == 'type_id')
                    $value = $model->getAttendeeTypeName();
                if ($attribute == 'country_id')
                    $value = $model->getCountryName();
                //                \app\components\ULog::Log($model);
                $model->save();
                // return JSON encoded output in the below format
                return ['output' => $value, 'message' => ''];

                // alternatively you can return a validation error
                // return ['output'=>'', 'message'=>'Validation error'];
            } // else if nothing to do always return an empty JSON encoded output
            else {
                return ['output' => '', 'message' => ''];
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContactEvent()
    {
        $searchModel = new SqlContactEvent();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('contact_event', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contact model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Contact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Contact();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Contact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Contact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

         return $this->redirect(['index']);
        return "";
    }

}
