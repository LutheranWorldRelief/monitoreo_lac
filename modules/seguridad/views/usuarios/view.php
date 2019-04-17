<?php
extract($activo);

use app\models\DataList;
use app\models\Project;
use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
$this->title = $model->first_name . ' ' . $model->last_name . ' / ' . $model->username;
$this->registerCssFile('@web/css/multiselect.listbox.custom.css', [
    'position' => View::POS_END,
]);
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <h1 class="pull-left"><?= $this->title; ?></h1>
        <?php echo Html::a('Modificar', Url::to(['usuarios/update', 'id' => $usuario->id]), ['class' => 'btn btn-primary pull-right']) ?>
    </div>
</div>
<div class="box">
    <div class="box-body">
        <?php
        $items = [
            [
                'label' => '<i class="fa fa-user"></i> Usuario',
                'content' => $this->render('view_user', ['model' => $model]),
                'bordered' => true,
                'active' => $activeUser
            ],
            [
                'label' => '<i class="fa fa-lock"></i> Roles',
                'content' => $this->render('view_permisos', ['model' => $model]),
                'bordered' => true,
                'active' => false
            ],
            [
                'label' => '<i class="fa fa-lock"></i> Paises',
                'content' => $this->render('view_multiselect', ['model' => $usuario, 'attribute' => 'countries', 'data' => DataList::itemsBySlug('countries')]),
                'bordered' => true,
                'active' => $activePais
            ],
            [
                'label' => '<i class="fa fa-lock"></i> Proyectos',
                'content' => $this->render('view_multiselect', ['model' => $usuario, 'attribute' => 'projects', 'data' => Project::listDataModel('nameCode')]),
                'bordered' => true,
                'active' => $activeProject
            ],
        ];

        $options = [
            'items' => $items,
            'position' => TabsX::POS_ABOVE,
            'align' => TabsX::ALIGN_LEFT,
            'bordered' => true,
            'encodeLabels' => false
        ];
        echo TabsX::widget($options);
        ?>
    </div>
</div>
<link href="<?= Url::to('@web/css/multiselect.listbox.custom.css') ?>" rel="stylesheet">