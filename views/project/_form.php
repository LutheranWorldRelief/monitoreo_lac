<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use \kartik\select2\Select2;
use app\components\UCatalogo;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box">
    <div class="box-body">
        <div class="project-form">

            <?php
            $form = ActiveForm::begin([
                "method" => "post",
                "enableClientValidation" => false,
                "options" => ["enctype" => "multipart/form-data"],
            ]);
            ?>
            <?= $form->errorSummary($model); ?>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2"><?=
                    $form->field($model, 'start')->widget(DatePicker::classname(), [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true
                        ]
                    ])
                    ?>
                </div>
                <div class="col-lg-2"><?=
                    $form->field($model, 'end')->widget(DatePicker::classname(), [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true
                        ]
                    ])
                    ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?= $form->field($model, 'goal_men')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?= $form->field($model, 'goal_women')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <?=
                    $form->field($model, 'logo')->widget(kartik\widgets\FileInput::classname(), [
                        'options' => ['multiple' => false, 'accept' => 'image/*'],
                        'pluginOptions' => [
                            'previewFileType' => 'image',
                            'showUpload' => false,
                            'overwriteInitial' => true,
                            'initialPreviewAsData' => true, 'initialPreview' => [
                                Yii::$app->urlManager->createAbsoluteUrl($model->logoPath()) . '/' . $model->logo
                            ],
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <?php for ($i = 1; $i < 10; $i++): ?>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <?php
                        $campo = 'color' . $i;
                        echo $form->field($model, $campo)->widget(ColorInput::classname(), [
                            'options' => ['placeholder' => 'Seleccione color ...'],
                        ]);
                        ?>
                    </div>
                <?php endfor; ?>
            </div>
            <div class="form-group">
                <?php $url = $model->isNewRecord ? \yii\helpers\Url::to(['index']) : \yii\helpers\Url::to(['view', 'id' => $model->id]); ?>
                <?= Html::a('<i class="fa fa-reply"></i> Cancelar', $url, ['class' => 'btn btn-danger']) ?>
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
