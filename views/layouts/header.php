<?php
$user = Yii::$app->user->identity;
if (!$user)
    $user = new app\models\AuthUser;

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?=
    Html::a(
        '<span class="logo-mini">LWR</span><span class="logo-lg">' . Yii::$app->name . '</span>',
        Yii::$app->homeUrl,
        [
            'class' => 'logo',
            'style' => 'background-color: #00AAA7;'
        ]
    ) ?>

    <nav class="navbar navbar-static-top" role="navigation" style="background-color: #00AAA7;">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Yii::getAlias('@web/img/logo_user_blanco.png') ?>"
                             class="user-image"
                             alt="User Image"/>
                        <span class="hidden-xs"><?= ucfirst($user->username) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= Yii::getAlias('@web/img/logo_user.jpg') ?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= $user->first_name . ' ' . $user->last_name ?>
                                <small><?= $user->email ?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    'Perfil',
                                    ['/site/profile'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Cerrar Sesión',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>