<?php 
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use app\components\Ulog;

if(!isset($options)) $options = [];
if(!isset($title)) $title = $this->title;
if(!isset($url)) $url = Url::to(['index']);

NavBar::begin([
	'brandLabel' => $title,
	'brandUrl' => $url,
	'innerContainerOptions' => [
		'style'=>'padding:0; width: 97%; margin: 0 25px',
	]
]);
echo Nav::widget([
    'items' => array_merge(
    	$options,
    	[
	    	[
	    		'label' => '<i class="fa fa-plus"></i> Nueva Lista', 
	    		'url' => ['data-list/create'], 
	    		'encode'=>false
	       	],
	    	[
	    		'label' => '<i class="fa fa-database"></i> Lista', 
	    		'url' => ['data-list/'], 
	    		'encode'=>false
	       	],
	    ]
	),
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();