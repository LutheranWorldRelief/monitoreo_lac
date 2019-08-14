<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Filter */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin([
    // 'layout' => 'horizontal',
]);
?>
<div class="row">
    <div class="col-lg-2"><?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?></div>

    <div class="col-lg-6"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>

    <div class="col-lg-12">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
