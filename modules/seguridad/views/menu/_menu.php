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
                'label' => '<i class="fa fa-plus"></i>'. Yii::t('app', 'Nuevo Usuario'),
                'url' => ['usuarios/create'],
                'encode' => false
            ],
            [
                'label' => '<i class="fa fa-plus"></i>'. Yii::t('app', 'Nuevo Rol'),
                'url' => ['roles/create'],
                'encode' => false
            ],
            [
                'label' => '<i class="fa fa-database"></i>'. Yii::t('app', 'Usuarios'),
                'url' => ['usuarios/'],
                'encode' => false
            ],
            [
                'label' => '<i class="fa fa-database"></i>'. Yii::t('app', 'Roles'),
                'url' => ['roles/'],
                'encode' => false
            ],
            [
                'label' => '<i class="fa fa-database"></i>'. Yii::('app', 'Rutas'),
                'url' => ['rutas/'],
                'encode' => false
            ],
            [
                'label' => '<i class="fa fa-database"></i>'. Yii::t('app', 'Bitácora'),
                'url' => ['/audit/'],
                'encode' => false
            ],
        ]
    ),
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();
