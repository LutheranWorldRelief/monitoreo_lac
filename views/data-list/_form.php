<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DataList */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin([
	// 'layout' => 'horizontal',
]); 
?>
<div class="row">
	<div class="col-lg-2"><?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?></div>

	<div class="col-lg-6"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>

	<div class="col-lg-1"><?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?></div>

	<div class="col-lg-3"><?= $form->field($model, 'tag')->textInput(['maxlength' => true]) ?></div>

	<div class="col-lg-12"><?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?></div>

	<div class="col-lg-12">
	    <div class="form-group">
	        <?= Html::submitButton('Guardar', ['class' =>'btn btn-primary']) ?>
	    </div>
	</div>
</div>
<?php ActiveForm::end(); ?>
