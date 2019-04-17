<?php

use app\assets\AlertifyAsset;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\DataList */
AlertifyAsset::register($this);
$this->title = 'Organization / ' . $model->name . ' - ' . $model->padre;
?>
<?= $this->render('_navbar', [
    'options' => [
        [
            'label' => '<i class="fa fa-pencil"></i> Actualizar',
            'url' => ['update', 'id' => $model->id],
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
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'description', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'countryNameText', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'padre', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'Implementer', 'class' => 'col-lg-3']) ?>
</div>
<div class="row">
    <?= $this->render('_list', [
        'provider' => new ArrayDataProvider([
            'allModels' => $model->contacts,
            'key' => 'id',
            'pagination' => false,
        ])
    ]); ?>
</div>
