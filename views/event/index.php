<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

// use app\components\ULog;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Event */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title .= ' / Event / Lista';
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute'=>'id',
    ],
    [
        'attribute' => 'name',
        'format' => 'raw',
        'value' => function ($model) {
            return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
        },
    ],
      [
        'attribute' => 'project',
        'label' => 'Project',
        'options' => ['style' => 'width: 170px;'],
    ],
    [
        'attribute' => 'structure',
        'label' => 'Estructure',
        'options' => ['style' => 'width: 170px;'],
    ], [
        'attribute' => 'country',
        'label' => 'Country',
        'options' => ['style' => 'width: 130px;'],
    ],
    [
        'attribute' => 'organization',
        'label' => 'Implementing Organization',
        'options' => ['style' => 'width: 130px;'],
    ],
    [
        'width' => '80px',
        'attribute' => 'start',
        'format' => 'raw',
        'options' => ['style' => 'width: 100px;'],
        'value' => function ($model) {
            return substr($model->start, 0, 10);
        },
    ],
    'h',
    'm',
    't',
   
];

?>
<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <?= ExportMenu::widget([
            'dataProvider' => $provider,
            'columns' => array_merge(
                $gridColumns,
                []
            )
        ]);
        ?>
        <?= GridView::widget([
            'id' => 'grid-details',
            'tableOptions' => [
                'class' => 'table table-condensed table-stripped',
            ],
            'dataProvider' => $provider,
            'filterModel' => $search,
            'columns' => array_merge(
                $gridColumns,
                [
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
                ]
            ),
            'pjax' => true,
            'pjaxSettings' => [
                'id' => 'grid-event-pjax',
            ]
        ]); ?>
    </div>
</div>