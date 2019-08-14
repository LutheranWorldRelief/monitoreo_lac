<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = 'Proyecto ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=
$this->render('_navbar', [
    'options' => [
        [
            'label' => '<i class="fa fa-users"></i>'. Yii::t('app', 'Beneficiarios'),
            'url' => ['project/contacts', 'projectId' => $model->id],
            'encode' => false
        ],
        [
            'label' => '<i class="fa fa-pencil"></i>'. Yii::t('app', 'Actualizar'),
            'url' => ['update', 'id' => $model->id],
            'linkOptions' => [
            ],
            'encode' => false
        ],
    ]
])
?>
<div class="project-view">
    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4">
            <p class="pull-right">
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?=
                Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ])
                ?>
            </p>
        </div>
    </div>
    <?php
    $this->title = 'Estructura';
    $this->params['breadcrumbs'][] = $this->title;
    $this->registerAssetBundle("\app\assets\AlertifyAsset", yii\web\View::POS_BEGIN);
    $this->registerAssetBundle("\app\assets\AngularTreeAsset", yii\web\View::POS_BEGIN);
    $this->registerJsFile("@web/js/script/project.structure.tree.js");
    ?>
    <div class="box"
         ng-app="treeGridStructure"
         ng-controller="treeGridController"
         ng-init="formulario.proyecto = <?= $model->id; ?>;cargarDatos()">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-8">
                    <h2>Estructura</h2>
                </div>
                <div class="col-sm-4">
                    <a class="pull-right btn btn-success"
		    href="<?= Url::toRoute(["structure/create", 'project' => $model->id]); ?>"><?= \Yii::t('app', 'Nueva Estructura')?></a>
                </div>
            </div>
            <button ng-click="my_tree.expand_all()" class="btn btn-primary btn-sm">Expand All</button>
            <button ng-click="my_tree.collapse_all()" class="btn btn-primary btn-sm">Collapse All</button>
            <br>
            <br>
            <input class="input-sm form-control" type="text" data-ng-model="filterString" placeholder="Filter"/>

            <tree-grid
                    tree-data="tree_data"
                    tree-control="my_tree"
                    col-defs="col_defs"
                    expand-on="expanding_property"
                    on-select="my_tree_handler(branch)"
                    icon-leaf="glyphicon glyphicon-record"
                    icon-expand="glyphicon glyphicon-tasks"
                    icon-collapse="glyphicon glyphicon-folder-open"
            >

            </tree-grid>

            <script>
                UrlsAcciones = {};
                UrlsAcciones.Proyecto = <?php echo $model->id; ?>;
                UrlsAcciones.UrlDatos = '<?php echo Url::toRoute("data-structure"); ?>';
                UrlsAcciones.UrlView = '<?php echo Url::toRoute("structure/update"); ?>';
                UrlsAcciones.UrlCreate = '<?php echo Url::toRoute("structure/create"); ?>';
                UrlsAcciones.UrlEliminar = '<?php echo Url::toRoute("structure/eliminar"); ?>';
            </script>
        </div>
    </div>


</div>
