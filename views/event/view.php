<?php

use app\assets\AlertifyAsset;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\DataList */
AlertifyAsset::register($this);
$this->title = 'Event';
?>
<?= $this->render('_navbar', [
    'options' => [
        [
            'label' => '<i class="fa fa-pencil"></i>'. Yii::t('app', "Actualizar"),
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
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'structure_id', 'class' => 'col-lg-1']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'organizer', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'start', 'class' => 'col-lg-2']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'end', 'class' => 'col-lg-2']) ?>
</div>
<div class="row">
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'implementingOrganizationName', 'class' => 'col-lg-6']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'countryName', 'class' => 'col-lg-6']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'title', 'class' => 'col-lg-6']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'project_name', 'class' => 'col-lg-6']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'structure_name', 'class' => 'col-lg-6']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'text', 'class' => 'col-lg-6']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'place', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'notes', 'class' => 'col-lg-12']) ?>
</div>
<div class="row">
    <?= $this->render('_list', [
        'provider' => new ArrayDataProvider([
            'allModels' => $model->attendances,
            'key' => 'id',
            'pagination' => false,
        ])
    ]); ?>
</div>
