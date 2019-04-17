<?php

namespace app\modules\seguridad\controllers;

use mdm\admin\controllers\RoleController;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use Yii;

class RolesController extends RoleController
{

    /**
     * @inheritdoc
     */
    public function labels()
    {
        return [
            'Item' => 'Rol',
            'Items' => 'Roles',
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AuthItemSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
