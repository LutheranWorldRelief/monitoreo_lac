<?php
/**
 * Created by PhpStorm.
 * User: NESTOR GONZALEZ
 * Date: 18 may 2018
 * Time: 03:53 PM
 */

use yii\bootstrap\ActiveForm;
use softark\duallistbox\DualListbox;

?>
<?php $form = ActiveForm::begin(); ?>
<div class="form-group pull-right">
    <?= \yii\helpers\Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<div class="clearfix"></div>
<?php
$options = [
    'multiple' => true,
    'size' => 20,
];

echo $form->field($model, $attribute)->widget(DualListbox::className(), [
    'items' => $data,
    'options' => $options,
    'clientOptions' => [
        'moveOnSelect' => true,
        'selectedListLabel' => 'Selected Items',
        'nonSelectedListLabel' => 'Available Items',
    ],
]);
?>
<hidden name="pais" value="1"></hidden>

<div class="clearfix"></div>
<?php ActiveForm::end(); ?>
