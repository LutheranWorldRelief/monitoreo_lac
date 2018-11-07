<?php

/* @var $this yii\web\View */
/* @var $models \app\components\ActiveRecord[]|\app\models\Contact[]|\app\models\SqlDebugContactDoc[]|\app\models\SqlDebugContactName[]|array|\yii\db\ActiveRecord[] */

use app\assets\Alertify1Asset;
use kartik\select2\Select2Asset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Url;

use app\assets\Vue2Asset;

BootstrapPluginAsset::register($this);
Select2Asset::register($this);
Vue2Asset::register($this);

$this->registerJsFile("@web/js/vue/comp.select2.js", ['depends' => [Vue2Asset::className()]]);
$this->registerJsFile("@web/js/vue/contact.merge.modules.js", ['depends' => [Alertify1Asset::className(), Vue2Asset::className()]]);
$this->registerJsFile("@web/js/vue/contact.merge.document.js", ['depends' => [Alertify1Asset::className(), Vue2Asset::className()]]);
?>
<?= $this->render('_vue_comp_select2') ?>
<div id="app" v-cloak data-baseurl="<?= Yii::$app->homeUrl ?>">
    <div v-if="loading.all">
        <img style="margin:0 auto; display: block" src="<?= Url::to('@web/img/loading.gif') ?>" alt="">
    </div>
    <div v-if="!loading.all" class="row" v-cloak>
        <div class="col-lg-12">
            <?= $this->render('_filter_form', [
                'projects' => $projects,
                'organizations' => $organizations,
                'countries' => $countries,
                'countryCode' => $countryCode,
                'projectId' => $projectId,
                'organizationId' => $organizationId,
            ]);
            ?>
            <h2>Casos : {{ modelsAll.length }}</h2>
        </div>
        <div class="col-lg-4" v-for="(model, index) in modelsAll">
            <div class="panel panel-default">
                <div class="panel-body" style="font-size: 12px">
                    {{index+1}}. {{model.document}} <span class="badge">{{model.cuenta}}</span>
                    <button
                            class="btn btn-xs btn-primary pull-right"
                            @click="preparingFusionForm(model)"
                            data-toggle="modal"
                            data-target="#modal-merge">
                        Fusionar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?= $this->render('_contact_merge_modal'); ?>
</div>