<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SqlUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <div class="sql-usuarios-index">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
            <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user'),]) ?>
            <?php Pjax::begin(); ?>    <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'username',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->name, ['usuarios/view', 'id' => $model->id]);
                        },
                    ],
                    'email:email',
                    'is_active',
                    'is_superuser',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
