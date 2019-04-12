<?php

use \app\models\form\ReportForm;
use \kartik\form\ActiveForm;
use \kartik\widgets\DatePicker;
use \kartik\select2\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\NavBar;
use \yii\helpers\Url;

/* @var ReportForm $model */
/* @var array $projects */
/* @var array $organizations */
/* @var array $countries */


NavBar::begin([
    'brandUrl' => Url::to(['report/']),
    'brandLabel' => 'Reportes',
    'innerContainerOptions' => [
        'style' => 'padding:0; width: 97%; margin: 0 25px',
    ]
]);
NavBar::end();
?>
<?php $form = ActiveForm::begin([
    'id' => 'report-form',
    'type' => ActiveForm::TYPE_VERTICAL
]);
?>
<div class="box box-body">
    <div class="row">
        <div class="col-lg-3">
            <?= Html::activeHiddenInput($model, 'send_it') ?>
            <?= $form->field($model, 'date_start')->widget(DatePicker::classname(), [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                ]
            ]);
            ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'date_end')->widget(DatePicker::classname(), [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                ]
            ]);
            ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'project_id')->widget(Select2::classname(), [
                'data' => $projects,
                'language' => 'es',
                'options' => ['placeholder' => 'Seleccionar...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'org_implementing_id')->widget(Select2::classname(), [
                'data' => $organizations,
                'language' => 'es',
                'options' => ['placeholder' => 'Seleccionar...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])
            ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'country_code')->widget(Select2::classname(), [
                'data' => $countries,
                'language' => 'es',
                'options' => ['placeholder' => 'Seleccionar...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-success"
               target="_blank"
               href="<?= Url::to(['report/template-clean']) ?>">
                <i class="fa fa-file"></i> Plantilla en Limpio
            </a>
            <button class="btn btn-primary pull-right" type="submit">
                <i class="fa fa-file"></i> Exportar
            </button>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
