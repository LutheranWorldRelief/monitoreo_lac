<?php
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;
use yii\helpers\Url;

echo $this->render('navbar');

$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 3,
    'attributes' => [
        'slug' => [
            'type' => Form::INPUT_TEXT, 
            'options' => [
                'placeholder'=>'slug'
            ]
        ],
        'description' => [
            'type' => Form::INPUT_TEXT, 
            'options' => [
                'placeholder'=>'description'
            ]
        ],
        'value' => [
            'type' => Form::INPUT_TEXT, 
            'options' => [
                'placeholder'=>'value'
            ]
        ],
    ]
]);
echo Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'tag' => [
            'type' => Form::INPUT_TEXT, 
            'options' => [
                'placeholder'=>'tags',
            ]
        ],
        'notes' => [
            'type' => Form::INPUT_TEXTAREA, 
            'options' => [
                'placeholder'=>'notes',
            ]
        ]
    ]
]);

$urlCancel = Url::to(['/data-list/index']);
if (!$model->isNewRecord)
    $urlCancel = Url::to(['/data-list/view', ['id'=>$model->id]]);
?>
<div class="col-md-12 text-right">
    <?= Html::a('Cancelar', $urlCancel, [
        'class'=> 'btn btn-danger']); 
    ?>
    <?= Html::button('Guardar', [
        'type'=>'submit', 
        'class'=>'btn btn-primary'
    ]); ?>
</div>
<?php
ActiveForm::end();
?>