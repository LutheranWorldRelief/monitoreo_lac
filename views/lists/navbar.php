<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => 'Data List',
    'brandUrl' => ['/lists/index'],
]);
echo Nav::widget([
    'items' => [
        [
            'label' => 'Nuevo',
            'url' => ['/lists/create']
        ],
    ],
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();
?>