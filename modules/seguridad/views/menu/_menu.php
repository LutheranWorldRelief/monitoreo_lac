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
                'label' => '<i class="fa fa-plus"></i> Nuevo Usuario',
                'url' => ['usuarios/create'],
                'encode'=>false
            ],
            [
                'label' => '<i class="fa fa-plus"></i> Nuevo Rol',
                'url' => ['roles/create'],
                'encode'=>false
            ],
            [
                'label' => '<i class="fa fa-database"></i> Usuarios',
                'url' => ['usuarios/'],
                'encode'=>false
            ],
            [
                'label' => '<i class="fa fa-database"></i> Roles',
                'url' => ['roles/'],
                'encode'=>false
            ],
            [
                'label' => '<i class="fa fa-database"></i> Rutas',
                'url' => ['rutas/'],
                'encode'=>false
            ],
            [
                'label' => '<i class="fa fa-database"></i> Bitácora',
                'url' => ['/audit/'],
                'encode'=>false
            ],
        ]
    ),
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();