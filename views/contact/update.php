<?php


/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title .= ' / Contacto / Nuevo';
echo $this->render('_navbar')
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>