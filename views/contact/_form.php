<?php

use app\components\UCatalogo;
use app\models\MonitoringEducation;
use app\models\MonitoringContactType;
use app\models\Country;
use app\models\Monitor;
use app\models\Organization;
use app\models\Profession;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $form yii\widgets\ActiveForm */
$form = ActiveForm::begin();
?>
<div class="box">
    <div class="box-body">
        <div class="contact-form">

            <div class="row">
                <div class="col-lg-4"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>
                <div class="col-lg-2"><?= $form->field($model, 'sex')->dropDownList(['F' => 'Femenino', 'M' => 'Masculino']) ?></div>
                <div class="col-lg-2"><?= $form->field($model, 'document')->textInput(['maxlength' => true]) ?></div>
                <div class="col-lg-2"><?= $form->field($model, 'phone_personal')->textInput(['maxlength' => true]) ?></div>

                <div class="col-lg-2"><?= $form->field($model, 'phone_work')->textInput(['maxlength' => true]) ?></div>

            </div>
            <div class="row">
                <div class="col-lg-4"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>

                <div class="col-lg-4">
                    <?= $form->field($model, 'organization_id')->widget(Select2::classname(), [
                        'data' => Organization::listData('name', 'id'),
                        'language' => 'es',
                        'options' => ['placeholder' => '...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-lg-2"><?= $form->field($model, 'women_home')->textInput() ?></div>
                <div class="col-lg-2"><?= $form->field($model, 'men_home')->textInput() ?></div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                        'data' => MonitoringContactType::allTypeContact(),
                        'language' => 'es',
                        'options' => ['placeholder' => '...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-lg-2"><?= $form->field($model, 'community')->textInput(['maxlength' => true]) ?></div>
                <div class="col-lg-2"><?= $form->field($model, 'municipality')->textInput(['maxlength' => true]) ?></div>
                <div class="col-lg-2"><?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?></div>

                <div class="col-lg-2">
                    <?= $form->field($model, 'country_id')->widget(Select2::classname(), [
                        'data' => Country::allCountries(),
                        'language' => 'es',
                        'options' => ['placeholder' => '...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'education_id')->widget(Select2::classname(), [
                        'data' => MonitoringEducation::allEducations(),
                        'language' => 'es',
                        'options' => ['placeholder' => '...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Education');
                    ?>
                </div>
            </div>

            <div class="col-lg-12 form-group">
                <?php
                if (!$model->isNewRecord)
                    echo Html::a('<i class="fa fa-reply"></i>'. Yii::t('app', "Cancelar"), ['view', 'id' => $model->id], ['class' => 'btn btn-danger']);
                else
                    echo Html::a('<i class="fa fa-reply"></i>'. Yii::t('app', "Cancelar"), ['index'], ['class' => 'btn btn-danger']);
                ?>
                <?= Html::submitButton('<i class="fa fa-floppy-o"></i>'. Yii::t('app', "Guardar"), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
