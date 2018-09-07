<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\Filter;

/* @var $this yii\web\View */
/* @var $model app\models\Filter */
/* @var $form yii\widgets\ActiveForm */

Modal::begin([
    'id'=>'detail-modal',
    'header' => '<h4>Agregar Item</h4>'
]);
?>
    <?php $form = ActiveForm::begin([
        'id'=>'detail-form',
        'action' => Url::to(['filter/save-detail', 'id'=>$model->id], true),
        'validationUrl' => Url::to(['filter/validate-detail'], true),
        'options'=>[
            'method'=>'POST',
            'onsubmit'=>'return false;',
        ]
    ]); ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($newModel, 'name')->textInput(['v-model'=>'model.name']) ?></div>
        <div class="col-md-6"><?= $form->field($newModel, 'order')->textInput(['v-model'=>'model.order']) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($newModel, 'start')->textInput(['v-model'=>'model.start']) ?></div>
        <div class="col-md-6"><?= $form->field($newModel, 'end')->textInput(['v-model'=>'model.end']) ?></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="saveNewDetail($event)">Guardar</button>
    </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end(); ?>

<?php
Modal::begin([
    'id'=>'detail-modal-edit',
    'header' => '<h4>Editar Item</h4>',
    'options'=>[
    ]
]);
?>
    <?php $form = ActiveForm::begin([
        'id'=>'detail-form-edit',
        'action' => Url::to(['filter/update-detail']),
        'validationUrl' => Url::to(['filter/validate-detail']),
        'options'=>[
            'method'=>'POST',
            'onsubmit'=>'return false;'
        ]
    ]); ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($newModel, 'name')->textInput(['v-model'=>'model.name']) ?></div>
        <div class="col-md-6"><?= $form->field($newModel, 'order')->textInput(['v-model'=>'model.order']) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($newModel, 'start')->textInput(['v-model'=>'model.start']) ?></div>
        <div class="col-md-6"><?= $form->field($newModel, 'end')->textInput(['v-model'=>'model.end  ']) ?></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click.prevent="saveDetail($event)">Guardar</button>
    </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end(); ?>