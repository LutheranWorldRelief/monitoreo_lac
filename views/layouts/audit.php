<?php
/* @var $this View */

/* @var $content string */

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\web\JSLoggingAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\debug\DebugAsset;
use yii\web\View;

DebugAsset::register($this);
JSLoggingAsset::register($this)
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<?=
Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label' => Yii::t('user', 'Usuarios'),
            'url' => ['/seguridad/usuarios'],
        ],
        [
            'label' => Yii::t('user', 'Roles'),
            'url' => ['/seguridad/roles'],
        ],
        [
            'label' => Yii::t('user', 'Rutas'),
            'url' => ['/seguridad/rutas'],
        ],
        [
            'label' => Yii::t('user', 'Bitácora'),
            'url' => ['/audit'],
        ],
        [
            'label' => Yii::t('user', 'Crear'),
            'items' => [
                [
                    'label' => Yii::t('user', 'Nuevo Usuario'),
                    'url' => ['/seguridad/usuarios/create'],
                ],
                [
                    'label' => Yii::t('user', 'Nuevo Rol'),
                    'url' => ['/seguridad/roles/create'],
                ],
            ],
        ],
    ],
])
?>

    <div class="row">
        <div class="col-lg-12">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::t('audit', 'Bitácora'),
                'brandUrl' => ['default/index'],
                'options' => ['class' => 'navbar-default'],
                'innerContainerOptions' => ['class' => 'container-fluid'],
            ]);

            $items = [['label' => Yii::t('audit', 'Entries'), 'url' => ['entry/index']],];
            foreach (Audit::getInstance()->panels as $panel) {
                /** @var Panel $panel */
                $indexUrl = $panel->getIndexUrl();
                if (!$indexUrl)
                    continue;
                $items[] = ['label' => $panel->getName(), 'url' => $indexUrl];
            }

            echo Nav::widget(['items' => $items, 'options' => ['class' => 'navbar-nav'],]);
            echo Nav::widget(['items' => [['label' => Yii::$app->name, 'url' => Yii::$app->getHomeUrl()],], 'options' => ['class' => 'navbar-nav navbar-right'],]);
            NavBar::end();
            ?>
        </div>
    </div>
<?= $content ?>
<?php
$this->endContent();
