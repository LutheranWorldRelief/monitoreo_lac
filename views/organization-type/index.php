<?php

use app\components\WGridView;
use app\components\WMenuExport;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrganizationType */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos de organizaciones';
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
            <?=
            WGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumns,
                'heading' => '<i class="wi wi-rain-mix wi-flip-horizontal"></i> Tipos de organizaciones',
                'toolbar' => [
                    WMenuExport::widget(['dataProvider' => $dataProvider, 'filename' => 'Tipos de organizaciones', 'columns' => $gridColumns]),
                    '{toggleData}',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
