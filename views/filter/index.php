<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Filter */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title .= ' / Filter / Lista';
?>
<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-condensed table-stripped',
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'headerOptions' => [
                        'style' => 'width:50px;'
                    ],
                    'attribute' => 'id',
                ],
                [
                    'headerOptions' => [
                        'style' => 'width:150px;'
                    ],
                    'attribute' => 'slug',
                ],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->name, ['filter/view', 'id' => $model->id]);
                    },
                ],

//                 'slug',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'mergeHeader' => false,
                    'width' => '150px',
                    'header' => '',
                    'contentOptions' => [
                        'class' => 'action-column',
                        'style' => 'width:150px',
                    ]
                ],
            ],
            'pjax' => true,
            'pjaxSettings' => [
            ]
        ]);
        ?>
    </div>
</div>