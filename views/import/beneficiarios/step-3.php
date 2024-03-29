<?php

use app\assets\Vue2Asset;
use yii\helpers\Json;
use yii\helpers\Url;

$this->registerJsFile("@web/js/vue/contact.merge.modules.js", ['depends' => [Vue2Asset::className()]]);
$this->registerJsFile("@web/js/vue/contact.merge.import.js", ['depends' => [Vue2Asset::className()]]);

extract($data);
?>
<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
	<h3 class="text-info pull-left"><?= \Yii::t('app', 'Proceso Finalizado con éxito')?></h3>
        <a href="<?= Url::to(['import/beneficiarios-paso1']) ?>"
	   class="btn btn-primary"><i class="fa fa-reply"></i><?= \Yii::t('app', 'Regresar')?></a>
    </div>

</div>
<div id="app" v-cloak class="row" data-baseurl="<?= Yii::$app->homeUrl ?>">
    <div class="col-lg-10 col-lg-offset-1 table-responsive">
        <script type="application/javascript">
            var gModels = <?= Json::encode($data) ?>;
        </script>
	<h3>{{ modelsNames.length }} <?= \Yii::t('app', 'Personas Posiblemente Duplicadas Respecto a Importados')?></h3>
        <table class="table table-bordered">
            <thead>
            <tr>
		<th><?= \Yii::t('app', 'N°')?></th>
		<th><?= \Yii::t('app', 'ID')?></th>
		<th><?= \Yii::t('app', 'Nombre')?></th>
		<th><?= \Yii::t('app', 'Sexo')?></th>
		<th><?= \Yii::t('app', 'Documento')?></th>
		<th><?= \Yii::t('app', 'Organización')?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(model, index) in modelsNames">
                <td>{{index + 1}}</td>
                <td>{{model.contact_id}}</td>
                <td>
                    <a :href="'<?= Url::to(['contact/view']) ?>?id=' + model.contact_id"
                       target="_blank">{{model.contact_name}}</a>
                </td>
                <td>{{model.contact_sex}}</td>
                <td>{{model.contact_document}}</td>
                <td>{{model.contact_organization}}</td>
                <td>
                    <button type="button"
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
    <?= $this->render('//opt/_contact_merge_modal'); ?>
</div>
<div style="clear: both;"></div>
</div>

