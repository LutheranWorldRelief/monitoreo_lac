<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\Contact */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'blurb') ?>

    <?php // echo $form->field($model, 'profile_image') ?>

    <?php // echo $form->field($model, 'qr_image') ?>

    <?php // echo $form->field($model, 'twitter_handle') ?>

    <?php // echo $form->field($model, 'organization_id') ?>

    <?php // echo $form->field($model, 'profession_id') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'area_desarrollo_mz') ?>

    <?php // echo $form->field($model, 'area_mz') ?>

    <?php // echo $form->field($model, 'area_productiva_mz') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'community') ?>

    <?php // echo $form->field($model, 'document') ?>

    <?php // echo $form->field($model, 'education') ?>

    <?php // echo $form->field($model, 'email_personal') ?>

    <?php // echo $form->field($model, 'email_work') ?>

    <?php // echo $form->field($model, 'men_home') ?>

    <?php // echo $form->field($model, 'municipality') ?>

    <?php // echo $form->field($model, 'phone_personal') ?>

    <?php // echo $form->field($model, 'phone_work') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'women_home') ?>

    <?php // echo $form->field($model, 'zip') ?>

    <?php // echo $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <?php // echo $form->field($model, 'ref') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'education_custom') ?>

    <?php // echo $form->field($model, 'monitor_id') ?>

    <?php // echo $form->field($model, 'type_tags') ?>

    <?php // echo $form->field($model, 'type_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
