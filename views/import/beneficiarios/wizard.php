<?php
/**
 * Created by PhpStorm.
 * User: NESTOR GONZALEZ
 * Date: 3 mar 2018
 * Time: 11:27 PM
 */

use app\assets\WizardAsset;
use yii\widgets\ActiveForm;

?>
<?php
$this->title = 'Importar Beneficiarios';
WizardAsset::register($this);
?>

<div class="box">
    <div class="box-body">
        <h3 class=""><?= \Yii::t('app', 'Importar Beneficiarios desde excel') ?></h3>
    </div>
</div>
<div class="wizard">
    <div class="wizard-inner">
        <div class="connecting-line"></div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="<?= $stepActive == 'step1' ? 'active' : '' ?>">
                <a href="#<?= $stepActive == 'step1' ? 'step' : '' ?>"
                   data-toggle="tab"
                   aria-controls="step1"
                   role="tab"
                   title=""
                   data-original-title="Step 1"
                   aria-expanded="true">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-folder-open"></i>
                            </span>
                </a>
            </li>

            <li role="presentation" class="<?= $stepActive == 'step2' ? 'active' : '' ?>">
                <a href="#<?= $stepActive == 'step2' ? 'step' : '' ?>"
                   data-toggle="tab"
                   aria-controls=""
                   role="tab"
                   title=""
                   data-original-title="Step 2"
                   aria-expanded="false">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </span>
                </a>
            </li>

            <li role="presentation" class="<?= $stepActive == 'step3' ? 'active' : '' ?>">
                <a href="#<?= $stepActive == 'step4' ? 'step' : '' ?>"
                   data-toggle="tab"
                   aria-controls=""
                   role="tab"
                   title=""
                   data-original-title="Complete"
                   aria-expanded="false">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane active container-fluid" role="tabpanel" id="step">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => "multipart/form-data"]]); ?>
            <?= $this->render($view, ['data' => $data]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div style="clear: both;"></div>
</div>
