<?php

use app\models\Structure;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Structure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box">
    <div class="structure-form box-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class='row'>

            <div class="col-lg-3">
                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-6">
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            </div>
            <div class="col-lg-6">
                <?php
                echo $form->field($model, 'structure_id')->widget(Select2::classname(), [
                    'data' => Structure::listDataBlank("description", $project),
                    'language' => 'es',
                    'options' => ['placeholder' => 'Seleccione un Usuario'],
                    'pluginOptions' => ['allowClear' => true],
                ])->label('parent');
                ?>

            </div>
        </div>
        <div class='row'>
            <div class="col-lg-12">
                <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
            </div>

        </div>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

