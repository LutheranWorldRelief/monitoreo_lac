<?php 
use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\DataList;

$this->registerCssFile('@web/js/lib/alertify/alertify.bootstrap.css');
$this->registerJsFile('@web/js/lib/alertify/alertify.min.js');

$this->registerJsFile('@web/js/axios/axios.min.js');
$this->registerJsFile('@web/js/vue/vue2.js');
$this->registerJsFile('@web/js/vue/datalist.view.vue.js', ['depends'=> ['yii\web\YiiAsset']]);

echo $this->render('navbar');

$m = new DataList;
?>
<div class="col-lg-12 page-header">
	<h3>
		<i class="fa fa-list"></i> List: <?=$model->description ?> /
		<small><?= $model->slug ?></small>
	</h3>
</div>
<div class="col-md-3" id="app-list">
	<?php
	$form = ActiveForm::begin([
		'id' => 'form-list',
    	'layout' => 'horizontal',
		'action' => Url::to(['lists/new-detail/', 'id'=>$model->id]),
		'validationUrl' => Url::to(['lists/validate/']),
		'enableAjaxValidation' => true,
		'options' => [
			'method'=>'POST',
		],
	]);
	?>
	<?=$form->field($m, 'description')->textInput(); ?>
	<?=$form->field($m, 'value'); ?>
	<?=$form->field($m, 'notes'); ?>

	<button 
		type="submit" 
		class="btn btn-sm btn-primary" >
		<i class="fa fa-disk"></i> Guardar
	</button> 
	<?php ActiveForm::end(); ?>
</div>
<div class="col-md-8">
	<?= GridView::widget([
		'id'=>'grid-details',
	    'dataProvider' => $provider,
	    'filterModel' => false,
	    'columns' => [
	        'description',
	        'value',
	        'notes',
	        [
	        	'class' => 'kartik\grid\ActionColumn',
	        ],
	    ],
	    'pjax' => true,
	    'pjaxSettings' => [
	        'neverTimeout' => true,
	    ]
	]); 
	?>
</div>