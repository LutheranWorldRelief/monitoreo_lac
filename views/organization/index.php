<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Organization */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organizations';
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'name',
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'description',
        'value' => function ($model) {
            return $model->description;
        },
        'editableOptions' => [
            'header' => 'Type',
            'asPopover' => false,
            'inputType' => kartik\editable\Editable::INPUT_TEXT,
            'options' => ['pluginOptions' => []]
        ]],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'country_id',
        'label' => 'Country',
        'content' => function ($model) {
            return $model->CountryNameText;
        },
//        'options' => ['style' => 'width: 170px;'],
        'filter' => [null => 'Todos'] + app\models\DataList::itemsBySlug('countries'),
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
            'data' => app\models\DataList::itemsBySlug('countries'),
            'options' => ['pluginOptions' => []]
        ]
    ],
//    [
//        'attribute' => 'country',
//        'label' => 'Country',
//        'content' => function($model) {
//            return $model->CountryName;
//        },
//        'format' => 'raw',
//        'options' => ['style' => 'width: 170px;'],
//        'filter' => \app\components\UCatalogo::listCountries()
//    ],
    [
        'attribute' => 'organization_id',
        'filter' => [null=>'Todos']+app\models\Organization::listData('name', 'id'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'size' => \kartik\select2\Select2::MEDIUM,
            'pluginOptions' => [
//                'allowClear' => true,
            ],
        ],
        'value' => function ($model) {
            return $model->padre;
        },
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'organization_type_id',
        'value' => function ($model) {
            return $model->TypeName;
        },
        'filter' =>[null=>'Todos']+ \app\models\OrganizationType::listData('name'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'size' => \kartik\select2\Select2::MEDIUM,
            'pluginOptions' => [
//                'allowClear' => true,
            ],
        ],
        'editableOptions' => [
            'header' => 'Type',
            'asPopover' => false,
            'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
            'data' => \app\models\OrganizationType::listData('name'),
            'options' => ['pluginOptions' => []]
        ]],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'is_implementer',
        'value' => function ($model) {
            return $model->Implementer;
        },
        'filter' => \app\components\UCatalogo::listSiNo(),
        'editableOptions' => [
            'header' => 'Type',
            'asPopover' => false,
            'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
            'data' => \app\components\UCatalogo::listSiNo(),
            'options' => ['pluginOptions' => []]
        ]],
//    [
//        'attribute' => 'organization_type_id',
//        'label' => 'Organization Type',
//        'content' => function($model) {
//            return $model->TypeName;
//        },
//        'format' => 'raw',
//        'options' => ['style' => 'width: 170px;'],
//        'filter' => \app\models\OrganizationType::listData('name')
//    ],
    ['class' => 'yii\grid\ActionColumn'],
];
?>
<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <div class="organization-index">
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
