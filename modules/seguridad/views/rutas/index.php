<?php

use mdm\admin\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $routes [] */

$this->title = 'Rutas';
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'routes' => $routes,
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-5">
                <div class="input-group">
                    <input class="form-control search" data-target="available"
                           placeholder="<?= Yii::t('rbac-admin', 'Buscar Disponibles'); ?>">
                    <span class="input-group-btn">
                <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['refresh'], [
                    'class' => 'btn btn-default',
                    'id' => 'btn-refresh',
                ]); ?>
            </span>
                </div>
                <select multiple size="20" class="form-control list" data-target="available"></select>
            </div>
            <div class="col-sm-1">
                <br><br>
                <?= Html::a('&gt;&gt;' . $animateIcon, ['assign'], [
                    'class' => 'btn btn-success btn-assign',
                    'data-target' => 'available',
                    'title' => Yii::t('rbac-admin', 'Assign'),
                ]); ?><br><br>
                <?= Html::a('&lt;&lt;' . $animateIcon, ['remove'], [
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
