<?php

/* @var $this yii\web\View */
/* @var $models \app\components\ActiveRecord[]|Contact[]|SqlDebugContactDoc[]|SqlDebugContactName[]|array|ActiveRecord[] */

/* @var array $projects */
/* @var array $organizations */
/* @var array $countries */
/* @var integer|null $projectId */
/* @var integer|null $organizationId */

/* @var string|null $countryCode */

use app\assets\Vue2Asset;
use app\models\Contact;
use app\models\SqlDebugContactDoc;
use app\models\SqlDebugContactName;
use kartik\select2\Select2Asset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\db\ActiveRecord;
use yii\helpers\Url;

BootstrapPluginAsset::register($this);
Select2Asset::register($this);
Vue2Asset::register($this);
$this->registerJsFile("@web/js/vue/comp.select2.js", ['depends' => [Vue2Asset::className()]]);
$this->registerJsFile("@web/js/vue/contact.merge.modules.js", ['depends' => [Vue2Asset::className()]]);
$this->registerJsFile("@web/js/vue/contact.merge.js", ['depends' => [Vue2Asset::className()]]);
?>
<div id="app" v-cloak data-baseurl="<?= Yii::$app->homeUrl ?>">
    <div v-if="loading.all">
        <img style="margin:0 auto; display: block" src="<?= Url::to('@web/img/loading.gif') ?>" alt="">
    </div>

    <div v-if="!loading.all" class="row" v-cloak>
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-body table-responsive" style="font-size: 12px">
                    <h2 class="text-maroon">{{ modelsNames.length }} Casos Duplicados por Nombre</h2>
                    <div class="row">
                        <div class="col-lg-12"><?= $this->render('_filter_form'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
				<th><?= \Yii::t('app', 'Caso N°')?></th>
				<th><?= \Yii::t('app', 'Nombre')?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(model, index) in modelsNames">
                                <tr v-if="(index <= parseInt( modelsNames.length/2))">
                                    <td> {{index+1}}</td>
                                    <td>{{model.name}} <span class="badge">{{model.cuenta}}</span></td>
                                    <td>
                                        <button
                                                class="btn btn-xs btn-primary pull-right"
                                                @click="preparingFusionForm(model)"
                                                data-toggle="modal"
                                                data-target="#modal-merge">
                                            Fusionar
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
				<th><?= \Yii::t('app', 'Caso N°')?></th>
				<th><?= \Yii::t('app', 'Nombre')?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(model, index) in modelsNames">
                                <tr v-if="(index > parseInt( modelsNames.length/2))">
                                    <td> {{index+1}}</td>
                                    <td>{{model.name}} <span class="badge">{{model.cuenta}}</span></td>
                                    <td>
                                        <button
                                                class="btn btn-xs btn-primary pull-right"
                                                @click="preparingFusionForm(model)"
                                                data-toggle="modal"
                                                data-target="#modal-merge">
                                            Fusionar
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?= $this->render('_contact_merge_modal'); ?>
</div>
