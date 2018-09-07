<?php
extract($activo);

use kartik\tabs\TabsX;

/* @var $this yii\web\View */
$this->title = $model->first_name . ' ' . $model->last_name . ' / ' . $model->username;
$this->registerCssFile('@web/css/multiselect.listbox.custom.css',[
    'position' => \yii\web\View::POS_END,
]);
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <h1 class="pull-left"><?= $this->title; ?></h1>
        <?php echo \yii\helpers\Html::a('Modificar', \yii\helpers\Url::to(['usuarios/update', 'id' => $usuario->id]), ['class' => 'btn btn-primary pull-right']) ?>
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
                'content' => $this->render('view_multiselect', ['model' => $usuario, 'attribute' => 'countries', 'data' => \app\models\DataList::itemsBySlug('countries')]),
                'bordered' => true,
                'active' => $activePais
            ],
            [
                'label' => '<i class="fa fa-lock"></i> Proyectos',
                'content' => $this->render('view_multiselect', ['model' => $usuario, 'attribute' => 'projects', 'data' => \app\models\Project::listDataModel('nameCode')]),
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
<link href="<?= \yii\helpers\Url::to('@web/css/multiselect.listbox.custom.css')?>" rel="stylesheet">