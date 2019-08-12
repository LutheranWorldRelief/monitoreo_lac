<?php

use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DataList */
/* @var $form yii\widgets\ActiveForm */

Modal::begin([
    'id' => 'detail-modal',
    'header' => '<h4>'.Yii::t('app', "Agregar Item")'</h4>'
]);
?>
<?php $form = ActiveForm::begin([
    'id' => 'detail-form',
    'action' => Url::to(['data-list/save-detail', 'id' => $model->id], true),
    'validationUrl' => Url::to(['data-list/validate-detail'], true),
    'options' => [
        'method' => 'POST',
        'onsubmit' => 'return false;',
    ]
]); ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($newModel, 'name')->textInput(['v-model' => 'model.name']) ?></div>
        <div class="col-md-6"><?= $form->field($newModel, 'order')->textInput(['v-model' => 'model.order']) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($newModel, 'value')->textInput(['v-model' => 'model.value']) ?>
            <div style="color: #dd4b39;">{{errors.value}}</div>
        </div>
        <div class="col-md-6"><?= $form->field($newModel, 'notes')->textInput(['v-model' => 'model.notes']) ?></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="saveNewDetail($event)">Guardar</button>
    </div>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>

<?php
Modal::begin([
    'id' => 'detail-modal-edit',
    'header' => '<h4>Editar Item</h4>',
    'options' => [
    ]
]);
?>
<?php $form = ActiveForm::begin([
    'id' => 'detail-form-edit',
    'action' => Url::to(['data-list/update-detail']),
    'validationUrl' => Url::to(['data-list/validate-detail']),
    'options' => [
        'method' => 'POST',
        'onsubmit' => 'return false;'
    ]
]); ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($newModel, 'name')->textInput(['v-model' => 'model.name']) ?></div>
        <div class="col-md-6"><?= $form->field($newModel, 'order')->textInput(['v-model' => 'model.order']) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($newModel, 'value')->textInput(['v-model' => 'model.value']) ?>
            <div style="color: #dd4b39;">{{errors.value}}</div>
        </div>
        <div class="col-md-6"><?= $form->field($newModel, 'notes')->textInput(['v-model' => 'model.notes']) ?></div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal"><?= \Yii::t('app', "Cerrar")</button>
    <button type="button" class="btn btn-primary" @click.prevent="saveDetail($event)"><?= \Yii::t('app', "Guardar")</button>
    </div>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>
