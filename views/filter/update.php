<?php

/* @var $this yii\web\View */
/* @var $model app\models\DataList */

$this->title .= ' / DataList / Actualizando : ' . $model->name;
echo $this->render('_navbar');
echo $this->render('_form', [
    'model' => $model,
]);
?>
