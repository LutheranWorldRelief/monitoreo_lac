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
<div id="app" v-cloak data-baseurl="<?= Yii::$app->homeUrl ?>">
    <div v-if="loading.all">
        <img style="margin:0 auto; display: block" src="<?= Url::to('@web/img/loading.gif') ?>" alt="">
    </div>
    <div v-if="!loading.all" class="row" v-cloak>
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-body table-responsive" style="font-size: 12px">
                    <h2 class="text-maroon">{{ modelsAll.length }} Casos Duplicados por Documento</h2>
                    <div class="row">
                        <div class="col-lg-12"><?= $this->render('_filter_form'); ?></div>
                    </div>
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Caso N°</th>
                            <th>Documento</th>
                            <th>Nombres</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(model, index) in modelsAll">
                            <td> {{index+1}}</td>
                            <td>{{model.document}} <span class="badge">{{model.cuenta}}</span></td>
                            <td>
                                <div v-html="model.name"></div>
                            </td>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?= $this->render('_contact_merge_modal'); ?>
</div>