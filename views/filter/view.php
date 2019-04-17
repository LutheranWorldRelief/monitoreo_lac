<?php

use app\assets\AlertifyAsset;
use app\models\Filter;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Filter */
AlertifyAsset::register($this);

$this->registerJsFile('@web/js/vue/vue2.js');
$this->registerJsFile('@web/js/filter.view.js', ['depends' => [
    'app\assets\AppAsset',
    'yii\web\JqueryAsset',
    'yii\web\YiiAsset',
]]);

$this->title .= ' / Filter / ' . $model->name;
echo $this->render('_navbar', [
    'options' => [
        [
            'label' => '<i class="fa fa-plus"></i> Nuevo Item',
            'url' => ['filter/update', 'id' => $model->id],
            'linkOptions' => [
                'data-toggle' => 'modal',
                'data-target' => '#detail-modal',
            ],
            'encode' => false
        ],
        [
            'label' => '<i class="fa fa-pencil"></i> Actualizar',
            'url' => ['filter/update', 'id' => $model->id],
            'linkOptions' => [
            ],
            'encode' => false
        ],
    ]
])
?>
<div class="row">
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'id', 'class' => 'col-lg-1']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'name', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'slug', 'class' => 'col-lg-2']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'filter_id', 'class' => 'col-lg-2']) ?>
</div>
<div class="row">
    <?= $this->render('_subform', ['model' => $model, 'newModel' => new Filter]); ?>
</div>
<div class="row">
    <?= $this->render('_list', [
        'provider' => new ArrayDataProvider([
            'allModels' => $model->getFilters()->orderBy('order DESC')->all(),
            'key' => 'id',
            'pagination' => false,
        ])
    ]); ?>
</div>