<?php

use app\models\AuthUser;
use kartik\form\ActiveForm;
use kartik\helpers\Html;

/* @var AuthUser $user */
?>

<h3>Perfil : <?= $user->first_name . ' ' . $user->last_name ?>
    <small> <?= $user->username ?> </small>
</h3>
<div class="row">
    <div class="col-lg-8">
        <div class="box box-primary">
            <div class="box-header">
                <h4>Datos</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'profile-form-data',
                'type' => ActiveForm::TYPE_VERTICAL,
            ]) ?>
            <div class="box-body">
                <?= $form->field($user, 'first_name') ?>
                <?= $form->field($user, 'last_name') ?>
                <?= $form->field($user, 'email') ?>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="box box-primary">
            <div class="box-header">
	    <h4><?= \Yii::t('app', 'Cambiar Contraseña')?></h4>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'profile-form-password',
                'type' => ActiveForm::TYPE_VERTICAL,
            ]) ?>
            <div class="box-body">
                <div class="form-group">
		<label class="control-label" for="password_current"><?= \Yii::t('app', 'Contraseña Actual')?></label>
                    <?= Html::passwordInput('Password[current]', null, ['class' => 'form-control']) ?>
                </div>
                <div class="form-group">
		    <label class="control-label" for="password_current"><?= \Yii::t('app', 'Contraseña Nueva')?></label>
                    <?= Html::passwordInput('Password[new]', null, ['class' => 'form-control']) ?>
                </div>
                <div class="form-group">
		    <label class="control-label" for="password_current"><?= \Yii::t('app', 'Contraseña Confirmación')?></label>
                    <?= Html::passwordInput('Password[confirm]', null, ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="box-footer">
		<button type="submit" class="btn btn-primary"><?= \Yii::t('app', 'Cambiar')?></button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
