<?php

/* @var $this yii\web\View */
/* @var $models \app\components\ActiveRecord[]|Contact[]|SqlDebugContactDoc[]|SqlDebugContactName[]|array|ActiveRecord[] */

/* @var array $projects */
/* @var array $organizations */
/* @var array $countries */
/* @var integer|null $projectId */
/* @var integer|null $organizationId */

/* @var string|null $countryCode */

use app\models\Contact;
use app\models\SqlDebugContactDoc;
use app\models\SqlDebugContactName;
use yii\bootstrap\Modal;
use yii\db\ActiveRecord;
use yii\helpers\Url;

Modal::begin([
    'id' => 'modal-merge',
    'header' => '<h4>FUSIONANDO / {{name}}</h4>',
    'size' => 'modal-lg',
]);
?>
    <div v-if="loading.modal">
        <img style="margin:0 auto; display: block" src="<?= Url::to('@web/img/loading.gif') ?>" alt="">
        <div class="callout callout-warning" v-if="loading.fusion">
	    <h4><?= \Yii::t('app', 'Fusionando Registros de Contacto...')?></h4>
        </div>
    </div>
    <div v-if="!loading.modal && models.length > 0">
        <!-- ---------------------------------------------------------------------------- SELECT -->
        <div v-if="modalState=='select'">
            <div class="callout callout-danger" v-cloak v-if="models.length <= 1">
		<h4><?= \Yii::t('app', 'No es posible fusionar solo un registro')?></h4>
            </div>
            <div class="callout callout-info" v-cloak v-if="models.length > 1">
		<h4><?= \Yii::t('app', 'Seleccione el registro que se usará como principal')?></h4>
            </div>
            <table class="table">
                <tr v-for="(model, index) in models">
                    <td><input type="radio" :value="model.id" v-model="modelSelected"></td>
                    <td>{{index+1}}</td>
                    <td>{{model.id}}</td>
                    <td><a :href="'<?= Url::to(['contact/view']) ?>?id=' + model.id" target="_blank">{{model.name}}</a>
                    </td>
                    <td>{{model.document}}</td>
                    <td>{{list_countries[model.country]}}</td>
                    <td>
                        <button type="button" class="btn btn-xs btn-primary pull-right"
                                v-if="models.length > 1"
                                @click="fusionExclude(model, index)">
                            Excluir
                        </button>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ---------------------------------------------------------------------------- RESOLVE -->
        <div v-if="modalState=='resolve'">
            <div class="callout callout-info" v-if="Object.keys(modelsResolve).length > 0">
		<h4><?= \Yii::t('app', 'Debe seleccionar una opción de los siguientes campos para establecerlo en el registro final')?></h4>
            </div>
            <table class="table" style="display: block; overflow-y: scroll; max-height: 500px;">
                <tr v-for="(values, key) in modelsResolve" v-if="showAttribute(key)">
                    <th>{{ modelLabels[key] }}</th>
                    <td>
                        <select class="form-control" v-model="modelMerge[key]" v-if="showField(key)">
                            <option v-for="value in values">{{ value }}</option>
                        </select>
                        <select class="form-control" v-model="modelMerge[key]" v-if="'country' == key">
                            <option v-for="value in values" :value="value">{{ list_countries[value] }}</option>
                        </select>
                        <select class="form-control" v-model="modelMerge[key]" v-if="'organization_id' == key">
                            <option v-for="value in values" :value="value">{{ list_organizations[value] }}</option>
                        </select>
                        <select class="form-control" v-model="modelMerge[key]" v-if="'type_id' == key">
                            <option v-for="value in values" :value="value">{{ list_types[value] }}</option>
                        </select>
                        <select class="form-control" v-model="modelMerge[key]" v-if="'education_id' == key">
                            <option v-for="value in values" :value="value">{{ list_education[value] }}</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="callout callout-info" v-if="modelsResolve.length == 0">
		<h4><?= \Yii::t('app', 'No hay datos que resolver. Por favor presione el botón "Resuelto" para pasar al siguiente paso.')?></h4>
            </div>
        </div>

        <!-- ---------------------------------------------------------------------------- FUSION -->
        <div v-if="modalState=='fusion'">
            <div class="callout callout-danger" v-if="errorFlags.fusion">
		<h4><?= \Yii::t('app', 'No fue posible fusionar el registro. Vuelva a Intentarlo')?></h4>
		<h5><?= \Yii::t('app', 'Si el problema persiste, contacte al desarrollador del sistema')?></h5>
            </div>
            <div class="callout callout-info" v-if="!loading.fusion && !errorFlags.fusion">
		<h4><?= \Yii::t('app', 'Se comenzará el proceso al presionar el botón "Fusionar"')?></h4>
            </div>
        </div>

        <!-- ---------------------------------------------------------------------------- FINISH -->
        <div v-if="modalState=='finish'">
            <div class="callout callout-danger" v-if="errorFlags.finish">
		<h4><?= \Yii::t('app', 'El proceso de fusión ha terminado pero con errores.')?></h4>
		<h5><?= \Yii::t('app', 'Por favor contacte al desarrollador del sistema e indique la siguiente información')?></h5>
                <pre>{{errorMessage.finish}}</pre>
            </div>
            <div class="callout callout-info" v-if="!errorFlags.finish">
		<h4><?= \Yii::t('app', 'Se ha finalizado el proceso de fusión. Presione Finalizar para recargar los datos de usuarios.')?></h4>
            </div>
            <!--
            <button class="btn btn-sm btn-info" @click="fusionFlags.result = !fusionFlags.result">
                <span v-if="!fusionFlags.result">Ver</span>
                <span v-if="fusionFlags.result">Ocultar</span> resultado
            </button>
            <br>
            <pre v-if="fusionFlags.result">{{fusionResult}}</pre>
            -->
        </div>
        <hr>
        <!-- ---------------------------------------------------------------------------- BUTTONS -->
        <button type="button" class="btn btn-large btn-danger" v-if="!loading.modal"
                @click="fusionCancelar('#modal-merge')">
            <span v-if="modalState != 'finish'">
                <i class="fa fa-ban"></i> Cancelar
            </span>
            <span v-if="modalState == 'finish'">
                <i class="fa fa-times"></i> Cerrar
            </span>
        </button>


        <button type="button" class="btn btn-large btn-primary pull-right"
                @click="fusionSelect"
                v-if="models.length > 1 && modalState == 'select' && modelSelected">
            <i class="fa fa-check"></i> Seleccionar
        </button>
        <button type="button" class="btn btn-large btn-primary pull-right"
                @click="fusionResolve"
                v-if="models.length > 1 && modalState == 'resolve'">
            <i class="fa fa-check"></i> Resuelto
        </button>
        <button type="button" class="btn btn-large btn-warning pull-right"
                @click="fusionStart"
                v-if="models.length > 1 && modalState == 'fusion'">
            <i class="fa fa-hand-o-right"></i> Fusionar
        </button>

        <button type="button" class="btn btn-large btn-primary pull-right"
                v-if="models.length > 1 && modalState == 'finish'"
                @click="fusionFinish"
                data-toggle="modal"
                data-target="#modal-merge">
            <i class="fa fa-check"></i> Finalizar
        </button>

        <a class="btn btn-large btn-primary pull-right"
           v-if="models.length > 1 && modalState == 'finish'"
           target="_blank"
           :href="'<?= Url::to(['contact/view']) ?>?id=' + modelSelected"><?= \Yii::t('app', 'Ver Registro Fusionado')?></a>
    </div>
<?php Modal::end(); ?>
