<?php

/* @var $this yii\web\View */
/* @var $models \app\components\ActiveRecord[]|Contact[]|SqlDebugContactDoc[]|SqlDebugContactName[]|array|ActiveRecord[] */

/* @var Project $project */

use app\models\Contact;
use app\models\SqlDebugContactDoc;
use app\models\SqlDebugContactName;
use yii\bootstrap\Modal;
use yii\db\ActiveRecord;
use yii\helpers\Url;

$modalId = 'modal-project-contact';

Modal::begin([
    'id' => $modalId,
    'header' => '<h4>Beneficiario/a: {{modal.contact.name}}</h4>',
    //    'size' => 'modal-lg',
    'clientEvents' => [
        'shown.bs.modal' => 'function(e){ appVue.modalLoadDatepicker(e); }',
    ]
]);
?>
    <div v-show="modal.loading">
        <img style="margin:0 auto; display: block" src="<?= Url::to('@web/img/loading.gif') ?>" alt="">
    </div>
    <div class="modal-body" v-show="!modal.loading && models.length > 0">
        <div class="row">
            <div class="col-lg-6" :class="{'has-error':modal.errors.product}">
                <label>{{labels.projectContact.product}}</label>
                <input type="text" class="form-control" v-model="modal.model.product">
                <div class="help-block" v-if="modal.errors.product">
                    <div v-for="error in modal.errors.product">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" :class="{'has-error':modal.errors.area}">
                <label>{{labels.projectContact.area}}</label>
                <input type="text" class="form-control" v-model="modal.model.area">
                <div class="help-block" v-if="modal.errors.area">
                    <div v-for="error in modal.errors.area">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6" :class="{'has-error':modal.errors.development_area}">
                <label>{{labels.projectContact.development_area}}</label>
                <input type="text" class="form-control" v-model="modal.model.development_area">
                <div class="help-block" v-if="modal.errors.development_area">
                    <div v-for="error in modal.errors.development_area">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" :class="{'has-error':modal.errors.productive_area}">
                <label>{{labels.projectContact.productive_area}}</label>
                <input type="text" class="form-control" v-model="modal.model.productive_area">
                <div class="help-block" v-if="modal.errors.productive_area">
                    <div v-for="error in modal.errors.productive_area">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6" :class="{'has-error':modal.errors.age_development_plantation}">
                <label>{{labels.projectContact.age_development_plantation}}</label>
                <input type="text" class="form-control" v-model="modal.model.age_development_plantation">
                <div class="help-block" v-if="modal.errors.age_development_plantation">
                    <div v-for="error in modal.errors.age_development_plantation">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" :class="{'has-error':modal.errors.age_productive_plantation}">
                <label>{{labels.projectContact.age_productive_plantation}}</label>
                <input type="text" class="form-control" v-model="modal.model.age_productive_plantation">
                <div class="help-block" v-if="modal.errors.age_productive_plantation">
                    <div v-for="error in modal.errors.age_productive_plantation">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6" :class="{'has-error':modal.errors.yield}">
                <label>{{labels.projectContact.yield}}</label>
                <input type="text" class="form-control" v-model="modal.model.yield">
                <div class="help-block" v-if="modal.errors.yield">
                    <div v-for="error in modal.errors.yield">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6" :class="{'has-error':modal.errors.date_entry_project}">
                <label>{{labels.projectContact.date_entry_project}}</label>
                <input type="text"
                       id="date_entry"
                       class="form-control datepicker"
                       v-model="modal.model.date_entry_project">
                <div class="help-block" v-if="modal.errors.date_entry_project">
                    <div v-for="error in modal.errors.date_entry_project">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" :class="{'has-error':modal.errors.date_end_project}">
                <label>{{labels.projectContact.date_end_project}}</label>
                <input type="text" id="date_end" class="form-control datepicker" v-model="modal.model.date_end_project">
                <div class="help-block" v-if="modal.errors.date_end_project">
                    <div v-for="error in modal.errors.date_end_project">
                        <small>{{ error }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ---------------------------------------------------------------------------- BUTTONS -->
        <div class="modal-footer">
            <div class="row">
                <button type="button"
                        class="btn btn-large btn-danger pull-left"
                        @click="modalCancel('#<?= $modalId ?>')">
                    <i class="fa fa-ban"></i> Cancelar
                </button>

                <button type="button" class="btn btn-large btn-primary"
                        @click="modalSave('#<?= $modalId ?>')">
                    <i class="fa fa-check"></i> Guardar
                </button>
            </div>
        </div>
    </div>
<?php Modal::end(); ?>