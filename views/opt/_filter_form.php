<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Json;

/* @var array $projects */
/* @var array $organizations */
/* @var array $countries */
/* @var integer|null $projectId */
/* @var integer|null $organizationId */
/* @var string|null $countryCode */

?>
<?php $form = ActiveForm::begin([
    'id' => 'report-form',
    'type' => ActiveForm::TYPE_VERTICAL
]);
?>
<div class="row">
    <script type="application/javascript">
        var modelFilter = <?= Json::encode([
          'projectId'     => $projectId ?: "",
          'organizationId'=> $organizationId ?: "" ,
          'countryCode'   => $countryCode ?: "",
        ]) ?>;
    </script>
    <div class="col-lg-4">
        <?= Html::dropDownList('projectId', $projectId, $projects, [
                'prompt' => '- Proyectos -',
                'class'=>'form-control',
                'v-model' => 'modelFilter.projectId'
        ]) ?>
    </div>
    <div class="col-lg-2">
        <?= Html::dropDownList('organizationId', $organizationId, $organizations, [
            'prompt' => '- Organizaciones -',
            'class'=>'form-control',
            'v-model' => 'modelFilter.organizationId'
        ]) ?>
    </div>
    <div class="col-lg-2">
        <?= Html::dropDownList('countryCode', $countryCode, $countries, [
            'prompt' => '- Paises -',
            'class'=>'form-control',
            'v-model' => 'modelFilter.countryCode'
        ]) ?>
    </div>
    <div class="col-lg-2">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-file"></i> Filtrar
        </button>
    </div>
</div>
<?php ActiveForm::end() ?>