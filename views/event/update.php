<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Event  / Actualizar';
echo $this->render('_navbar')
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
