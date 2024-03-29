<?php

use app\models\Country;
use app\models\MonitoringContactType;
use app\components\WGridView;
use app\models\Organization;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Contact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title .= ' / Contacto / Lista';
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:30px']
    ],
    [
        'attribute' => 'name',
        'format' => 'raw',
        'value' => function ($model) {
            return Html::a($model->name, ['contact/view', 'id' => $model->id]);
        },
    ],
    [
        'attribute' => 'document',
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'country_id',
        'value' => function ($model) {
            return $model->countryName;
        },
        'filter' => [null => 'Todos'] + Country::allCountries(),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => ['size' => Select2::MEDIUM],
        'editableOptions' => [
            'header' => 'Country',
            'asPopover' => false,
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => Country::allCountries(),
        ]],
    [
        'attribute' => 'organization_id',
        'filter' => [null => 'Todos'] + Organization::listData('name', 'id'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => ['size' => Select2::MEDIUM],
        'value' => function ($model) {
            return $model->organizationName;
        },
        'headerOptions' => ['style' => 'width:250px']
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'type_id',
        'filter' => [null => 'Todos'] + MonitoringContactType::allTypeContact(),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => ['size' => Select2::MEDIUM],
        'value' => function ($model) {
            return $model->attendeeTypeName;
        },
        'editableOptions' => [
            'header' => 'Tipo',
            'asPopover' => false,
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => MonitoringContactType::allTypeContact(),
            'options' => ['pluginOptions' => []]
        ]],
    [
        'attribute' => 'title',
    ],
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
];
$gridColumnsExcel = array_merge($gridColumns, []);
$gridColumnsExcel[2] = ['attribute' => 'name',];
unset($gridColumnsExcel[0]);
unset($gridColumnsExcel[8]);
?>

<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <?=
        WGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
            'heading' => '<i class="wi wi-rain-mix wi-flip-horizontal"></i>'. Yii::t('app', "Contactos"),
            /*'toolbar' => [
                WMenuExport::widget(['dataProvider' => $dataProvider, 'filename' => 'Contactos', 'columns' => $gridColumnsExcel]),
                '{toggleData}',
            ],*/
        ]);
        ?>
    </div>
</div>
