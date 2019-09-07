<?php

use app\assets\AppAsset;
use bedezign\yii2\audit\web\JSLoggingAsset;
use dmstr\web\AdminLteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $content string */

#JSLoggingAsset::register($this);

if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render('login', ['content' => $content]);
} else {
    AppAsset::register($this);
    AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link rel="shortcut icon" href="<?= Yii::$app->urlManager->createAbsoluteUrl("img/logo_user.png") ?>">
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <?= $this->render('content.php', ['content' => $content]) ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>

<style>
    .skin-blue .main-header .navbar {
        background-color: #00AAA7;
    }

    .skin-blue .main-header .logo {
        background-color: #00AAA7;
        color: #fff;
        border-bottom: 0 solid transparent;
    }

    .skin-blue .main-header li.user-header {
        background-color: #00AAA7;
    }

    .sidebar-mini.sidebar-collapse .content-wrapper,
    .sidebar-mini.sidebar-collapse .right-side,
    .sidebar-mini.sidebar-collapse .main-footer {
        margin-left: 0 !important;
    }

    .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side {
        background-color: transparent;
    }

</style>
