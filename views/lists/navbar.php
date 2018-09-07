<?php 
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;

NavBar::begin([
	'brandLabel' => 'Data List',
	'brandUrl' => ['/lists/index'],
]);
echo Nav::widget([
    'items' => [
        [
        	'label' => 'Nuevo', 
        	'url' => [ '/lists/create' ]
        ],
    ],
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();
?>