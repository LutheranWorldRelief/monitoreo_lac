<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DataList */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title .= ' / DataList / Lista';
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
                        return Html::a($model->name, ['data-list/view', 'id' => $model->id]);
                    },
                ],
                [
                    'headerOptions' => [
                        'style' => 'width:100px;'
                    ],
                    'attribute' => 'tag',
                ],
                [
                    'headerOptions' => [
                        'style' => 'width:100px;'
                    ],
                    'attribute' => 'value',
                ],
                // 'list_id',
                // 'notes:ntext',
                // 'slug',
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