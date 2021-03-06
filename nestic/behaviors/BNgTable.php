<?php

namespace app\nestic\behaviors;

use Yii;
use yii\base\Behavior;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;

class BNgTable extends Behavior
{

    public function ngTable($class)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $orden = $request->getQueryParam('orden');
        $filtros = ArrayHelper::merge(Json::decode($request->getQueryParam('filtros')), Json::decode($request->getQueryParam('extra')));
        $query = $class::ngFiltros($filtros, $orden, $class);

        $pageSize = $request->getQueryParam('tamanio_pagina');
        if (empty($pageSize))
            $pageSize = 10;

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'defaultPageSize' => $pageSize,
        ]);

        $headers = Yii::$app->response->headers;
        $headers->add('total_registros', $countQuery->count());

        return $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
    }

}
