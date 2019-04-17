<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

if (!isset($options)) $options = [];
if (!isset($title)) $title = $this->title;
if (!isset($url)) $url = Url::to(['index']);

NavBar::begin([
    'brandLabel' => $title,
    'brandUrl' => $url,
    'innerContainerOptions' => [
        'style' => 'padding:0; width: 97%; margin: 0 25px',
    ]
]);
echo Nav::widget([
    'items' => array_merge(
        $options,
        [
            [
                'label' => '<i class="fa fa-plus"></i> Nuevo Evento',
                'url' => ['event/create'],
                'encode' => false
            ],
            [
                'label' => '<i class="fa fa-database"></i> Lista',
                'url' => ['event/'],
                'encode' => false
            ],
        ]
    ),
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();