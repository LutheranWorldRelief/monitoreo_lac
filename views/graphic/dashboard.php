<?php

use yii\helpers\Url;

$this->registerAssetBundle("\app\assets\HighchartsAsset", yii\web\View::POS_BEGIN);
$this->registerAssetBundle("\app\assets\AngularAsset", yii\web\View::POS_BEGIN);
$this->registerJsFile("@web/js/servicios_angular/ng-grid.js");
$this->registerCssFile("@web/js/servicios_angular/ng-grid.css");
$this->registerJsFile("@web/js/servicios_angular/highcharts.optiones.js");
$this->registerJsFile("@web/js/script/dashboard_index.js");
$logo = Yii::$app->urlManager->createAbsoluteUrl("img/logo/");
?>
<div ng-app="App" ng-controller="AppCtrl" ng-cloak="">
    <!--    Encabezado-->
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?= \yii\helpers\Html::img(Yii::$app->urlManager->createAbsoluteUrl("img/logo.png"), ['class' => 'image-responsive']) ?>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h1 class="text-center" style="color: #3A3A3A;">Sistema de Monitoreo de Proyectos</h1>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <img class="image-responsive" style="width:100%; height: auto;"
                         ng-src="<?= $logo ?>{{proyecto.logo}}" alt="" ng-if="proyecto !== null">
                </div>

            </div>
        </div>
    </div>

    <div ng-if="cargando">
        <img style="margin:0 auto; display: block" src="<?= Url::to('@web/img/loading.gif') ?>" alt="">
    </div>
    <section ng-show="!cargando" class="row" >
        <!--Filtros-->
        <?= $this->render('dashboard/filtros'); ?>

        <!--Resumen-->
        <?= $this->render('dashboard/resumen'); ?>

        <!--Paises y Rubros-->
        <?= $this->render('dashboard/paises_rubros'); ?>

        <!--Metas-->
        <?= $this->render('dashboard/metas'); ?>


        <!--Metas-->
        <?= $this->render('dashboard/nacionalidad'); ?>

        <!-- Organizaciones, Pastel, Fiscal   -->
        <?= $this->render('dashboard/organizaciones_pastel_fiscal'); ?>

        <!--Edad y Educación-->
        <?= $this->render('dashboard/educacion'); ?>

        <!--Eventos y Tipo-->
        <?= $this->render('dashboard/eventos_tipo'); ?>
    </section>
</div>
<script>
    UrlsAcciones = {};
    UrlsAcciones.nombreGrafico = 'PARTICIPANTES POR ACTIVIDAD';
    UrlsAcciones.UrlDatosProyecto = '<?php echo Url::toRoute("proyecto"); ?>';
    UrlsAcciones.UrlDatosCantidadProyectos = '<?php echo Url::toRoute("cantidad-proyectos"); ?>';
    UrlsAcciones.UrlDatosCantidadEventos = '<?php echo Url::toRoute("cantidad-eventos"); ?>';
    UrlsAcciones.UrlDatosGraficoActividades = '<?php echo Url::toRoute("grafico-actividades"); ?>';
    UrlsAcciones.UrlDatosPaises = '<?php echo Url::toRoute("paises"); ?>';
    UrlsAcciones.UrlDatosRubros = '<?php echo Url::toRoute("rubros"); ?>';
    UrlsAcciones.UrlDatosGraficoOrganizaciones = '<?php echo Url::toRoute("grafico-organizaciones"); ?>';
    UrlsAcciones.UrlDatosProyectosMetas = '<?php echo Url::toRoute("proyectos-metas"); ?>';
    UrlsAcciones.UrlDatosGraficoAnioFiscal = '<?php echo Url::toRoute("grafico-anio-fiscal"); ?>';
    UrlsAcciones.UrlDatosGraficoEdad = '<?php echo Url::toRoute("grafico-edad"); ?>';
    UrlsAcciones.UrlDatosGraficoEducacion = '<?php echo Url::toRoute("grafico-educacion"); ?>';
    UrlsAcciones.UrlDatosGraficoEventos = '<?php echo Url::toRoute("grafico-eventos"); ?>';
    UrlsAcciones.UrlDatosGraficoTipoParticipante = '<?php echo Url::toRoute("grafico-tipo-participante"); ?>';
    UrlsAcciones.UrlDatosGraficoNacionalidad = '<?php echo Url::toRoute("grafico-nacionalidad"); ?>';
    UrlsAcciones.UrlDatosGraficoPaisEventos = '<?php echo Url::toRoute("grafico-pais-eventos"); ?>';
    UrlsAcciones.UrlLogo = '<?php echo Yii::$app->urlManager->createAbsoluteUrl("img/logo.png"); ?>';
</script>
<style>
    .bg-blue, .callout.callout-success, .alert-success, .label-success, .modal-success .modal-body {
        background-color: #00AAA7 !important;
        }

    .bg-green, .callout.callout-success, .alert-success, .label-success, .modal-success .modal-body {
        background-color: #C1CD23 !important;
        }
</style>
<link href="<?= \yii\helpers\Url::to('@web/css/checkbox.css') ?>" rel="stylesheet">