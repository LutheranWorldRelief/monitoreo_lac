<?php

use app\components\UCatalogo;
use app\components\WGridView;
use app\components\WMenuExport;
use app\models\DataList;
use app\models\OrganizationType;
use kartik\editable\Editable;
use kartik\select2\Select2;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Organization */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organizaciones';
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'name',
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'description',
        'value' => function ($model) { return $model->description; },
        'editableOptions' => [
            'header' => 'Type',
            'asPopover' => false,
            'inputType' => Editable::INPUT_TEXT,
            'options' => ['pluginOptions' => []]
        ]],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'country_id',
        'label' => 'Country',
        'content' => function ($model) { return $model->CountryNameText; },
        'filter' => [null => 'Todos'] + DataList::itemsBySlug('countries'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => ['size' => Select2::MEDIUM,],
        'editableOptions' => [
            'header' => 'Country',
            'asPopover' => false,
            'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
            'data' => app\models\DataList::itemsBySlug('countries'),
            'options' => ['pluginOptions' => []]
        ]
    ],
    [
        'attribute' => 'organization_id',
        'filter' => [null => 'Todos'] + app\models\Organization::listData('name', 'id'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => ['size' => Select2::MEDIUM],
        'value' => function ($model) { return $model->padre; },
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'organization_type_id',
        'value' => function ($model) { return $model->TypeName; },
        'filter' => [null => 'Todos'] + OrganizationType::listData('name'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => ['size' => Select2::MEDIUM],
        'editableOptions' => [
            'header' => 'Type',
            'asPopover' => false,
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => OrganizationType::listData('name'),
        ]],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'is_implementer',
        'value' => function ($model) { return $model->Implementer; },
        'filter' => UCatalogo::listSiNo(),
        'editableOptions' => [
            'header' => 'Type',
            'asPopover' => false,
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => UCatalogo::listSiNo(),
        ]],

    ['class' => 'yii\grid\ActionColumn'],
];
?>
<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <div class="organization-index">
            <?=
            WGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumns,
                'heading' => '<i class="wi wi-rain-mix wi-flip-horizontal"></i> Organizaciones',
                'toolbar' => [
                    WMenuExport::widget(['dataProvider' => $dataProvider, 'filename' => 'Organizaciones', 'columns' => $gridColumns]),
                    '{toggleData}',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
