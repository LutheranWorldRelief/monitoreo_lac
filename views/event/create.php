<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title .= ' / Event / Nuevo';
echo $this->render('_navbar')
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
