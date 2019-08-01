<?php

use app\models\Attendeetype;
use app\models\DataList;
use app\models\Organization;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\JSON;
use yii\helpers\Url;

$depends = [
    'app\assets\AppAsset',
    'yii\web\JqueryAsset',
    'yii\web\YiiAsset',
];

$this->registerJsFile('@web/js/lib/typeahead.js', ['depends' => $depends]);
$this->registerJsFile('@web/js/vue/vue2.js');
$this->registerJsFile('@web/js/event.form.js', ['depends' => $depends]);

$form = ActiveForm::begin();
?>
<div id="event-form">
    <div class="box box-body">
        <div class="row">
            <div class="col-lg-6"><?= $form->field($model, 'name')->textarea(['rows' => 4]) ?></div>
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'structure_id')->widget(Select2::classname(), [
                    'data' => app\models\Structure::listDataBlank('description'),
                    'language' => 'es',
                    'options' => ['placeholder' => 'Seleccione una actividad'],
                    'pluginOptions' => ['allowClear' => true],
                ])->label('Structure');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2"><?= $form->field($model, 'organizer')->textInput(['maxlength' => true]) ?></div>
            <div class="col-lg-2"><?=
                $form->field($model, 'start')->widget(DatePicker::classname(), [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ])
                ?>
            </div>
            <div class="col-lg-2"><?=
                $form->field($model, 'end')->widget(DatePicker::classname(), [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ])
                ?>
            </div>
            <div class="col-lg-6"><?= $form->field($model, 'place')->textInput(['maxlength' => true]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?php
                echo $form->field($model, 'organization_id')->widget(Select2::classname(), [
                    'data' => Organization::listData('name', 'id'),
                    'language' => 'es',
                    'options' => ['placeholder' => 'Seleccione una Organización'],
                    'pluginOptions' => ['allowClear' => true],
                ])->label('Implementing Organization');
                ?>
            </div>
            <div class="col-md-4">
                <?php
                echo $form->field($model, 'country_id')->widget(Select2::classname(), [
                    'data' => DataList::itemsBySlug('countries'),
                    'language' => 'es',
                    'options' => ['placeholder' => 'Seleccione un país'],
                    'pluginOptions' => ['allowClear' => true],
                ])->label('Country');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6"><?= $form->field($model, 'text')->textarea(['rows' => 4]) ?></div>
            <div class="col-lg-5"><?= $form->field($model, 'notes')->textarea(['rows' => 4]) ?></div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <script type="text/json" id="vue-options"><?= JSON::encode([
                            'app' => [
                                'url' => [
                                    'validate_attendance' => Url::to(['contact/find-all', 'q' => ''])
                                ]
                            ],
                            'attendances' => $model->attendancesArray ? $model->attendancesModels : $model->attendances,
                            'errors' => $model->errors,
                        ]);
                        ?>

                    </script>
                    <div v-if="errors.attendancesArray">
                        <div class="alert alert-danger">
                            Favor verificar los datos proporcionados.
                            <ul>
                                <li v-for="err in errors.attendancesArray">{{err}}</li>
                            </ul>
                        </div>
                    </div>
                    <table class="table table-condensed table-stripped table-bordered">
                        <thead>
                        <tr>
                            <th>Participante</th>
                            <th style="max-width: 64px;"></th>
                            <th>Documento</th>
                            <th style="width:70px">Sexo</th>
                            <th style="width:130px">Organizacion</th>
                            <th>País</th>
                            <th>Comunidad</th>
                            <th>Tipo</th>
                            <th style="width:100px">Cell</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(att, index) in attendances">
                            <td>
                                <input
                                        style="display:none"
                                        class="form-control"
                                        :name="'Event[attendancesArray][' + index + '][contact_id]'"
                                        v-model="att.contact_id"
                                        type="text">
                                <autocomplete
                                        autocomplete="off"
                                        class="form-control"
                                        :class="{ linked : att.contact_id, linked_no : att.contact_id == '' }"
                                        url="<?= Url::to(['contact/find-all', 'q' => '']) ?>"
                                        :name="'Event[attendancesArray][' + index + '][fullname]'"
                                        :value="att.fullname"
                                        @select="afterSelectContact($event, att, index)"
                                        @keydown="nameKeyDown($event, att)"
                                        @keyup="nameKeyUp($event, att)"
                                        type="text"></autocomplete>
                                <div v-if="att.errors.fullname" class="has-error">
                                    <div class="help-block" v-for="error in att.errors.fullname">
                                        {{error}}
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align: middle; padding-left: 10px;">
                                <a tabindex="-1"
                                   target="_blank"
                                   :href="'<?= Url::to(['contact/view']) ?>?id=' + att.contact_id"
                                   v-show="att.contact_id">
                                    <i class="fa fa-link"></i>
                                </a>
                            </td>
                            <td>
                                <autocomplete
                                        autocomplete="off"
                                        class="form-control"
                                        :class="{ linked : att.contact_id, linked_no : att.contact_id == '' }"
                                        url="<?= Url::to(['contact/find-doc', 'q' => '']) ?>"
                                        :name="'Event[attendancesArray][' + index + '][document]'"
                                        :value="att.document"
                                        :disabled="att.contact_id ? true : false"
                                        @select="afterSelectDoc($event, att, index)"
                                        @keydown="docKeyDown($event, att)"
                                        @keyup="docKeyUp($event, att)"
                                        type="text"></autocomplete>
                                <div v-if="att.errors.document" class="has-error">
                                    <div class="help-block" v-for="error in att.errors.document">
                                        {{error}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <select class="form-control"
                                        :name="'Event[attendancesArray]['+index+'][sex]'"
                                        v-model="att.sex">
                                    <option value="F">F</option>
                                    <option value="M">M</option>
                                </select>
                            </td>
                            <td>
                                <input
                                        style="display:none"
                                        class="form-control"
                                        :name="'Event[attendancesArray][' + index + '][org_id]'"
                                        v-model="att.org_id"
                                        type="text">
                                <autocomplete
                                        autocomplete="off"
                                        class="form-control"
                                        autocomplete="off"
                                        :class="{ linked : att.org_id, linked_no : att.org_id == '' }"
                                        url="<?= Url::to(['organization/find-all', 'q' => '']) ?>"
                                        :name="'Event[attendancesArray][' + index + '][org_name]'"
                                        :value="att.org_name"
                                        @select="afterSelectOrg($event, att, index)"
                                        @keydown="orgKeyDown($event, att)"
                                        @keyup="orgKeyUp($event, att)"
                                        type="text"></autocomplete>
                            </td>
                            <td>
                                <?=
                                Html::dropDownList('country', '', DataList::itemsBySlug('countries', 'name', 'value'), [
                                        'class' => 'form-control',
                                        ':name' => "'Event[attendancesArray]['+index+'][country]'",
                                        'v-model' => 'att.country'
                                    ]
                                )
                                ?>
                            </td>
                            <td>
                                <input class="form-control"
                                       :name="'Event[attendancesArray]['+index+'][community]'"
                                       type="text"
                                       v-model="att.community">
                            </td>
                            <td>
                                <?=
                                Html::dropDownList('type_id', '', DataList::itemsBySlug('participantes'), [
                                    'class' => 'form-control',
                                    ':name' => "'Event[attendancesArray]['+index+'][type_id]'",
                                    'v-model' => 'att.type_id'
                                ])
                                ?>
                                <div v-if="att.errors.type_id" class="has-error">
                                    <div class="help-block" v-for="error in att.errors.type_id">
                                        {{error}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input class="form-control"
                                       :name="'Event[attendancesArray]['+index+'][phone_personal]'"
                                       type="text"
                                       v-model="att.phone_personal">
                            </td>
                            <td>
                                <?=
                                Html::button('<i class="fa fa-trash"></i>', [
                                    'class' => 'btn btn-xs btn-danger',
                                    '@click' => 'removeAttendance(att, index)'
                                ])
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="col-lg-12">
                        <?= Html::button('<i class="fa fa-plus"></i> Agregar Participante', ['class' => 'btn btn-primary pull-right', '@click' => 'newAttendance']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php if ($model->id): ?>
                <?= Html::a('<i class="fa fa-reply"></i> Cancelar', ['view', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
            <?php else: ?>
                <?= Html::a('<i class="fa fa-reply"></i> Cancelar', ['index'], ['class' => 'btn btn-danger']) ?>
            <?php endif; ?>
            <?= Html::submitButton('<i class="fa fa-floppy-o"></i> Guardar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

