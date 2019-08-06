<?php

use app\components\UString;
use app\models\MonitoringEducation;
use app\models\Country;
use app\models\DataList;
use app\models\Organization;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

extract($data);
$form = ActiveForm::begin(['options' => ['enctype' => "multipart/form-data",]]);

?>
<?= Html::hiddenInput("guardar", true); ?>
<div class="row">
    <div class="col-lg-4 col-lg-offset-1">
        <h3 class="text-info pull-left">Paso 2: Ajustar Datos</h3>
    </div>
    <div class="col-lg-7">
        <ul class="list-inline pull-right">
            <li>
                <a class="btn btn-danger pull-left" href="<?= Url::to(['import/beneficiarios-paso1']) ?>">
                    <i class="fa fa-backward"></i> Cancelar
                </a>
            </li>
            <?php if (count($data['Correcto']) > 0): ?>
                <li>
                    <button type="submit" class="btn btn-success btn-block pull-right"><i
                                class="fa fa-upload fa-w-16"></i>
                        Importar y continuar
                    </button>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<br>
<hr>
<?php
if (!is_null($errores)):
    ?>
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 alert alert-danger">
            <?= $errores ?>
        </div>
    </div>
<?php endif; ?>
<?php
if (count($data['Guardar']) < 1):
    ?>
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 alert alert-danger">
            No se importará ningún registro
        </div>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-lg-4 col-lg-offset-1">
        País de la importación
        <?=
        Select2::widget([
            'name' => "pais",
            'data' => Country::allCountries(),
            'language' => 'es',
            'options' => ['placeholder' => '...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-lg-8 col-lg-offset-3">
        <ul class="nav nav-pills" id="myTabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#crear" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Registros
                                                                                                                      a Crear</a>
            </li>
            <?php if (count($data['Guardar']) > 0): ?>
                <li role="presentation" class="">
                    <a href="#correcto" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile"
                       aria-expanded="false">Datos
                                             a Importar</a>
                </li>
            <?php endif; ?>
            <?php if (count($data['Incorrecto']) > 0): ?>
                <li role="presentation" class="">
                    <div class="alert alert-danger">
                        <a href="#errores" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile"
                           aria-expanded="false">Datos
                                                 que no se importarán</a>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade active in" role="tabpanel" id="crear" aria-labelledby="home-tab">
        <h4 class="text-info" style="padding: 20px">Registros a Crear</h4>
        <div class="row" style="padding: 20px">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 table-responsive">
                        <h3>Proyectos</h3>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($proyectosRegistrar as $d): ?>
                                <tr>
                                    <td><?= $d['project_code'] ?></td>
                                    <td><?= $d['project_name'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 table-responsive">
                        <h3>Organizaciones</h3>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Organización</th>
                                <th>Vincular con</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $key = 0;
                            foreach ($organizacionRegistrar as $d): ?>
                                <tr>
                                    <td><?= $d['organization_name'] ?></td>
                                    <td>
                                        <?= Html::hiddenInput("organizacion[$key][nombre]", $d['organization_name']); ?>
                                        <?=
                                        Select2::widget([
                                            'name' => "organizacion[$key][vincular_con]",
                                            'data' => Organization::listData('name', 'id'),
                                            'language' => 'es',
                                            'options' => ['placeholder' => '...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]);
                                        ?>
                                    </td>
                                </tr>
                                <?php $key++; endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 table-responsive">
                        <h3>Paises</h3>
                        <table class="table table-bordered">
                            <?php foreach ($paisesRegistrar as $d): ?>
                                <tr>
                                    <td><?= $d['country_name'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                    <div class="col-lg-6 table-responsive">
                        <h3>Educación</h3>
                        <table class="table table-bordered">
                            <?php $key = 0;
                            foreach ($educacionRegistrar as $d): ?>
                                <tr>
                                    <td><?= $d['education_name'] ?></td>
                                    <td>
                                        <?= Html::hiddenInput("educacion[$key][nombre]", $d['education_name']); ?>
                                        <?=
                                        Select2::widget([
                                            'name' => "educacion[$key][vincular_con]",
                                            'data' => MonitoringEducation::allEducations(),
                                            'language' => 'es',
                                            'options' => ['placeholder' => '...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]);
                                        ?>
                                    </td>
                                </tr>
                                <?php $key++; endforeach; ?>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="tab-pane fade" role="tabpanel" id="correcto" aria-labelledby="profile-tab">
        <div class="row">
            <div class="col-lg-11 col-lg-offset-0">
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <h3>Datos a importar</h3>
                        <?php if (count($data['Guardar']) > 0) echo UString::array2Table($data['Guardar']); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="tab-pane fade" role="tabpanel" id="errores" aria-labelledby="dropdown1-tab">
        <div class="row">
            <div class="col-lg-11 col-lg-offset-0">
                <?php if (count($data['Incorrecto']) > 0): ?>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <h3>Datos Incorrectos</h3>
                            <div class="alert alert-error">Verifique datos de proyecto, organización implementadora y
                                                           beneficiario para:
                            </div>
                            <table class="table table-bordered">
                                <?php foreach ($data['Incorrecto'] as $d): ?>
                                    <tr>
                                        <td><?= $d ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-danger pull-left" href="<?= Url::to(['import/beneficiarios-paso1']) ?>">
            <i class="fa fa-backward"></i> Cancelar
        </a>
    </li>
    <?php if (count($data['Guardar']) > 0): ?>
        <li>
            <button type="submit" class="btn btn-success btn-block pull-right"><i class="fa fa-upload fa-w-16"></i>
                Importar y continuar
            </button>
        </li>
    <?php endif; ?>
</ul>
<div style="clear: both;"></div>
<?php ActiveForm::end(); ?>
</div>

