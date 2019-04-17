<?php
/* @var $this View */

/* @var $content string */

use app\components\UArchivos;
use yii\web\View; ?>
<?php $this->beginPage() ?>
<!--<html lang="<?= Yii::$app->language ?>">-->
<style><?php echo UArchivos::getContentFile('@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css'); ?></style>
<body>
<!--    <div style="position: absolute;  right: -20; top:190; bottom: 0;">
            <img src="<?= yii\helpers\Url::to("@webroot/images/fondo.jpg") ?>" style="margin: 0;" />
        </div>-->
<?php $this->beginBody() ?>
<div class="container-fluid"><?= $content ?></div>
<?php $this->endBody() ?>
</body>
<!--</html>-->
<?php $this->endPage() ?>

