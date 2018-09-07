<?php

use mdm\admin\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = $labels['Item'] .' : ' .$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <div class="auth-item-view">
            <h1 class="pull-left"><?= Html::encode($this->title); ?></h1>
            <p class="pull-right">
                <?= Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']); ?>
                <?= Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->name], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]); ?>
                <?= Html::a(Yii::t('rbac-admin', 'Create'), ['create'], ['class' => 'btn btn-success']); ?>
            </p>
            <div class="row">
                <div class="col-sm-11">
                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'name:text:Nombre',
                            'description:ntext:Descripción',
                        ],
                        'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <input class="form-control search" data-target="available"
                           placeholder="<?= Yii::t('rbac-admin', 'Buscar Disponibles'); ?>">
                    <select multiple size="20" class="form-control list" data-target="available"></select>
                </div>
                <div class="col-sm-1">
                    <br><br>
                    <?= Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
                        'class' => 'btn btn-success btn-assign',
                        'data-target' => 'available',
                        'title' => Yii::t('rbac-admin', 'Assign'),
                    ]); ?><br><br>
                    <?= Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
                        'class' => 'btn btn-danger btn-assign',
                        'data-target' => 'assigned',
                        'title' => Yii::t('rbac-admin', 'Remove'),
                    ]); ?>
                </div>
                <div class="col-sm-5">
                    <input class="form-control search" data-target="assigned"
                           placeholder="<?= Yii::t('rbac-admin', 'Buscar Asignadas'); ?>">
                    <select multiple size="20" class="form-control list" data-target="assigned"></select>
                </div>
            </div>
        </div>
    </div>
</div>
