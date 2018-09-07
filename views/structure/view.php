<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Structure */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Structures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="structure-view box box-primary" style="padding: 15px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary  pull-right']) ?>


    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
                'id',
            'code',
            'description:ntext',
            'structure_id',
            'notes:ntext',
            'project_id',
    ],
    ]) ?>

</div>
