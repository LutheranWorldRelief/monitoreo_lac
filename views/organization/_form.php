<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\UCatalogo;
/* @var $this yii\web\View */
/* @var $model app\models\Organization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organization-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class='row'>
        <div class="col-lg-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?=
            $form->field($model, 'country_id')->widget(Select2::classname(), [
                'data' => app\models\DataList::itemsBySlug('countries'),
                'language' => 'es',
                'options' => ['placeholder' => '...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-lg-3">
            <?=
            $form->field($model, 'organization_id')->widget(Select2::classname(), [
                'data' => \app\models\Organization::listDataBlank('name'),
                'language' => 'es',
                'options' => ['placeholder' => '...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-lg-3">
            <?=
            $form->field($model, 'organization_type_id')->widget(Select2::classname(), [
                'data' => \app\models\OrganizationType::listDataBlank('name'),
                'language' => 'es',
                'options' => ['placeholder' => '...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-lg-3">
            <?=
            $form->field($model, 'is_implementer')->widget(Select2::classname(), [
                'data' => UCatalogo::listSiNo(),
                'language' => 'es',
                'options' => ['placeholder' => '...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>




    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
