<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrganizationType */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organization Types';
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'abbreviation',
    'name',
    'description',
    [
        'class' => 'yii\grid\ActionColumn',
        'header' => 'Actions',
        'headerOptions' => ['style' => 'color:#337ab7'],
        'template' => '{update}{delete}',
        ]];
?>
<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <div class="organization-type-index">
            <?= kartik\export\ExportMenu::widget(['dataProvider' => $dataProvider, 'columns' => array_merge($gridColumns, [])]); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumns,
            ]);
            ?>
        </div>
    </div>
</div>
