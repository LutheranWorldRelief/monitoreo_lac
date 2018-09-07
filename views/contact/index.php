<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\components\UCatalogo;
use app\models\Organization;
use app\models\Attendeetype;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Contact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title .= ' / Contacto / Lista';
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'id',
        'headerOptions' => [
            'style' => 'width:30px',
        ]
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
        'attribute' => 'country',
        'value' => function ($model) {
            return $model->countryName;
        },
        'filter' => [null => 'Todos'] + UCatalogo::listCountries(),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'size' => \kartik\select2\Select2::MEDIUM,
            'pluginOptions' => [
//                'allowClear' => true,
            ],
        ],
        'editableOptions' => [
            'header' => 'Country',
            'asPopover' => false,
            'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
            'data' => UCatalogo::listCountries(),
            'options' => ['pluginOptions' => []]
        ]],
//    [
//        'attribute' => 'country',
//        'filter' => UCatalogo::listCountries(),
//        'value' => function($model) {
//            return $model->countryName;
//        },
//    ],
    [
        'attribute' => 'organization_id',
        'filter' => [null => 'Todos'] + Organization::listData('name', 'id'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'size' => \kartik\select2\Select2::MEDIUM,
            'pluginOptions' => [
//                'allowClear' => true,
            ],
        ],
        'value' => function ($model) {
            return $model->organizationName;
        },
        'headerOptions' => [
            'style' => 'width:250px',
        ]
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'type_id',
        'filter' => [null => 'Todos'] + app\models\DataList::itemsBySlug('participantes'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'size' => \kartik\select2\Select2::MEDIUM,
            'pluginOptions' => [
//                'allowClear' => true,
            ],
        ],
        'value' => function ($model) {
            return $model->attendeeTypeName;
        },
        'editableOptions' => [
            'header' => 'Tipo',
            'asPopover' => false,
            'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
            'data' => app\models\DataList::itemsBySlug('participantes'),
            'options' => ['pluginOptions' => []]
        ]],
//    [
//        'attribute' => 'type_id',
//        'filter' => app\models\DataList::itemsBySlug('participantes'),
//        'value' => function($model) {
//            return $model->attendeeTypeName;
//        },
//    ],
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
        <?= kartik\export\ExportMenu::widget(['dataProvider' => $dataProvider, 'columns' => $gridColumnsExcel]); ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
        ]);
        ?>
    </div>
</div>
