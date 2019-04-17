<?php

use app\assets\Alertify1Asset;
use app\assets\Vue2Asset;
use app\models\Project;
use kartik\date\DatePickerAsset;
use yii\helpers\Url;

/* @var $project Project */
/* @var $this yii\web\View */

$this->title = 'Beneficiarios / <small>' . $project->name . '</small>';
$this->params['breadcrumbs'][] = $this->title;

DatePickerAsset::register($this);
Alertify1Asset::register($this);
Vue2Asset::register($this);
$depends = [
    DatePickerAsset::className(),
    Alertify1Asset::className(),
    Vue2Asset::className()
];

$this->registerJsFile("@web/js/vue/project.contact.url.js", ['depends' => $depends]);
$this->registerJsFile("@web/js/vue/project.contact.js", ['depends' => $depends]);
?>
<?= $this->render('_navbar', [
    'options' => [
        [
            'label' => '<i class="fa fa-folder-open"></i> Proyecto',
            'url' => ['project/view', 'id' => $project->id],
            'encode' => false
        ],
    ]
]) ?>
<div class="box" id="app" v-cloak data-baseurl="<?= Yii::$app->homeUrl ?>" data-project-id="<?= $project->id ?>">
    <div class="box-body">
        <div class="project-contacts-index">
            <div class="row">
                <div class="col-lg-2">
                    <input type="text" class="form-control" v-model="filter" placeholder="Buscar...">
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary" @click="search(filter)">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-primary" @click="search()" v-if="filter.length > 0">
                        Todos
                    </button>
                </div>
            </div>
            <div v-if="loading">
                <img style="margin:0 auto; display: block" src="<?= Url::to('@web/img/loading.gif') ?>" alt="">
                <h4 class="text-center text-primary">Cargando...</h4>
            </div>
            <div v-if="!loading">
                <h4>Total Beneficiarios {{count.contacts}}</h4>
                <table class="table table-condensed" style="font-size: 12px;">
                    <thead>
                    <tr>
                        <th style="width: 80px">{{labels.contact.id}}</th>
                        <th>{{labels.contact.name}}</th>
                        <th style="width: 80px"></th>
                        <th style="width: 120px">{{labels.projectContact.product}}</th>
                        <th style="width: 70px">{{labels.projectContact.area}}</th>
                        <th style="width: 90px">{{labels.projectContact.development_area}}</th>
                        <th style="width: 90px">{{labels.projectContact.productive_area}}</th>
                        <th style="width: 120px">{{labels.projectContact.age_development_plantation}}</th>
                        <th style="width: 120px">{{labels.projectContact.age_productive_plantation}}</th>
                        <th style="width: 50px">{{labels.projectContact.yield}}</th>
                        <th style="width: 100px">{{labels.projectContact.date_entry_project}}</th>
                        <th style="width: 100px">{{labels.projectContact.date_end_project}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(model, index) in models">
                        <td>{{model.id}}</td>
                        <td>
                            <a :href="'<?= Url::to(['contact/view']) ?>?id=' + model.id"
                               target="_blank">{{model.name | uppercase}}</a>
                        </td>
                        <td>
                            <button
                                    type="button"
                                    class="btn btn-xs btn-warning"
                                    @click="modalEdit(model, index)"
                                    data-toggle="modal"
                                    data-target="#modal-project-contact">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.product}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.area}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.development_area}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.productive_area}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.age_development_plantation}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.age_productive_plantation}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.yield}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.date_entry_project}}</td>
                        <td v-if="model.projectContactOne">{{model.projectContactOne.date_end_project}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?= $this->render('_modal_project_contact'); ?>
    </div>
</div>