<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthUser */

$this->title = 'Usuario / Modificar: ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Auth Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <div class="auth-user-update">

            <h3><?= Html::encode($this->title) ?></h3>

            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>

        </div>
    </div>
</div>