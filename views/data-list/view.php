<?php

use app\assets\AlertifyAsset;
use app\models\DataList;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\DataList */
AlertifyAsset::register($this);

$this->registerJsFile('@web/js/vue/vue2.js');
$this->registerJsFile('@web/js/datalist.view.vue.js', ['depends' => [
    'app\assets\AppAsset',
    'yii\web\JqueryAsset',
    'yii\web\YiiAsset',
]]);

$this->title .= ' / DataList / ' . $model->name;
echo $this->render('_navbar', [
    'options' => [
        [
            'label' => '<i class="fa fa-plus"></i> Nuevo Item',
            'url' => ['data-list/update', 'id' => $model->id],
            'linkOptions' => [
                'data-toggle' => 'modal',
                'data-target' => '#detail-modal',
            ],
            'encode' => false
        ],
        [
            'label' => '<i class="fa fa-pencil"></i> Actualizar',
            'url' => ['data-list/update', 'id' => $model->id],
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
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'value', 'class' => 'col-lg-2']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'data_list_id', 'class' => 'col-lg-2']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'tag', 'class' => 'col-lg-2']) ?>
</div>
<div class="row">
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'notes', 'class' => 'col-lg-6']) ?>
</div>
<div class="row">
    <?= $this->render('_subform', ['model' => $model, 'newModel' => new DataList]); ?>
</div>
<div class="row">
    <?= $this->render('_list', [
        'provider' => new ArrayDataProvider([
            'allModels' => $model->getDataLists()->orderBy('order DESC')->all(),
            'key' => 'id',
            'pagination' => false,
        ])
    ]); ?>
</div>