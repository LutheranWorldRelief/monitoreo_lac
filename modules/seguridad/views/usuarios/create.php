<?php

/* @var $this yii\web\View */
/* @var $model app\models\AuthUser */

$this->title = 'Usuario / Crear';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Usuarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <div class="auth-user-create">
            <?= $this->render('_form', ['model' => $model,]) ?>
        </div>
    </div>
</div>
