<?php
//use yii\helpers\Html;
//use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Structure */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;

$this->title = 'Estructura';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAssetBundle("\app\assets\AngularTreeAsset", yii\web\View::POS_BEGIN);
$this->registerJsFile("@web/js/script/project.structure.tree.js");
//$this->registerCssFile("@web/js/servicios_angular/treeGrid.css");
?>
<?= $this->render('_navbar') ?>
<div class="box" ng-app="treeGridStructure" ng-controller="treeGridController" ng-init="cargarDatos()">
    <div class="box-body">

        <button ng-click="my_tree.expand_all()" class="btn btn-default btn-sm">Expand All</button>
        <button ng-click="my_tree.collapse_all()" class="btn btn-default btn-sm">Collapse All</button>
        <input class="input-sm pull-right" type="text" data-ng-model="filterString" placeholder="Filter"/>

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
            UrlsAcciones.UrlDatos = '<?php echo Url::toRoute("data-structure"); ?>';
        </script>
    </div>
</div>
