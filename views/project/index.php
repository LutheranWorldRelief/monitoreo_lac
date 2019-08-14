<?php

use app\components\UNumero;
use app\components\WGridView;
use app\components\WMenuExport;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Project */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'code',
    ],
    [
        'attribute' => 'name',
        'format' => 'raw',
        'value' => function ($model) { return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]); },
    ],
    [
        'attribute' => 'countries',
    ],
    [
        'attribute' => 'goal_men',
        'value' => function ($model) { return UNumero::FormatoNumero((int)$model->goal_men, 0); },
    ],
    [
        'attribute' => 'goal_women',
        'value' => function ($model) { return UNumero::FormatoNumero((int)$model->goal_women, 0); },
    ],
    [
        'attribute' => 'h',
        'value' => function ($model) { return UNumero::FormatoNumero((int)$model->h, 0); },
    ],
    [
        'attribute' => 'm',
        'value' => function ($model) { return UNumero::FormatoNumero((int)$model->m, 0); },
    ],
    [
        'attribute' => 't',
        'value' => function ($model) { return UNumero::FormatoNumero((int)$model->t, 0); },
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'header' => 'Actions',
        'headerOptions' => ['style' => 'color:#337ab7'],
        'template' => '{update}{delete}{view}',
    ],
];
?>
<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <?=
        WGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
            'heading' => '<i class="wi wi-rain-mix wi-flip-horizontal"></i>'. Yii::t('app', 'Proyectos'),
        ]);
        ?>
    </div>
</div>
