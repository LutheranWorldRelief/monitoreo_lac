<?php

use app\assets\AlertifyAsset;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $this yii\web\View */
/* @var $model app\models\DataList */
AlertifyAsset::register($this);
$this->title = 'Contacto / ' . $model->id . ' / ' . $model->fullname;
?>
<?= $this->render('_navbar', [
    'title' => null,
    'url' => null,
    'options' => [
        [
            'label' => '<i class="fa fa-pencil"></i> Actualizar',
            'url' => ['update', 'id' => $model->id],
            'linkOptions' => [],
            'encode' => false
        ],
    ]
])
?>
<h1><?= $model->name ?></h1>
<div class="row">
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'title', 'class' => 'col-lg-2']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'countryName', 'class' => 'col-lg-2']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'sex', 'class' => 'col-lg-1']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'city', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'community', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'document', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'educationName', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'organizationName', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'men_home', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'municipality', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'phone_personal', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'phone_work', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'otherPhones', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'women_home', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'attendeeTypeName', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'created', 'class' => 'col-lg-3']) ?>
    <?= $this->render('_view', ['model' => $model, 'attrib' => 'modified', 'class' => 'col-lg-3']) ?>
</div>

<section>
    <!-- Custom tabs (Charts with tabs)-->
    <div class="nav-tabs-custom" style="cursor: move;">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-right ui-sortable-handle">
            <li class=""><a href="#events" data-toggle="tab" aria-expanded="false">Events</a></li>
            <li class=""><a href="#activities" data-toggle="tab" aria-expanded="false">Activities</a></li>
            <li class="active"><a href="#projects" data-toggle="tab" aria-expanded="true">Projects</a></li>
            <li class="pull-left header"><i class="fa fa-inbox"></i> Log</li>
        </ul>
        <div class="tab-content no-padding">
            <div class="chart tab-pane active" id="projects">
                <?= $this->render('_list_project', [
                    'provider' => new ArrayDataProvider([
                        'allModels' => $model->projects,
                        'key' => 'id',
                        'pagination' => false,
                    ])
                ]); ?>
            </div>
            <div class="chart tab-pane" id="activities" style="position: relative; min-height: 300px;">

                <?= $this->render('_list_activities', [
                    'provider' => new ArrayDataProvider([
                        'allModels' => $model->structures,
                        'key' => 'id',
                        'pagination' => false,
                    ])
                ]); ?>
            </div>
            <div class="chart tab-pane" id="events" style="position: relative; min-height: 300px;">

                <?= $this->render('_list', [
                    'provider' => new ArrayDataProvider([
                        'allModels' => $model->events,
                        'key' => 'id',
                        'pagination' => false,
                    ])
                ]); ?>
            </div>
        </div>
    </div>


</section>

<div class="row">

</div>