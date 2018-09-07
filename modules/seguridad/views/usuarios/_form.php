<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AuthUser */
/* @var $form yii\widgets\ActiveForm */

$this->registerAssetBundle("\yii\web\JqueryAsset", yii\web\View::POS_HEAD);
\app\assets\iCheckAsset::register($this);
?>
<div class="auth-user-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'is_active')->checkbox(['inputOptions' => ['class' => 'icheckbox_square-green']]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'is_superuser')->checkbox(['inputOptions' => ['class' => 'icheckbox_square-green']]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?></div>

    </div>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>
    </div>

</div>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_square-green'
        });
    });
</script>