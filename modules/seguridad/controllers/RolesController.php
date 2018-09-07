<?php

namespace app\modules\seguridad\controllers;

use mdm\admin\models\AuthItem;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;
use \mdm\admin\components\Helper;

class RolesController extends \mdm\admin\controllers\RoleController {

    /**
     * @inheritdoc
     */
    public function labels() {
        return[
            'Item' => 'Rol',
            'Items' => 'Roles',
        ];
    }

    public function actionIndex() {
        $searchModel = new AuthItemSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

}
