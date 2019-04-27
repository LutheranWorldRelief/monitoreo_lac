<?php
/* @var $this View */

/* @var $content string */

use app\components\UArchivos;
use yii\web\View;

?>
<?php $this->beginPage() ?>
<!--<html lang="<?= Yii::$app->language ?>">-->
<style><?php echo UArchivos::getContentFile('@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css'); ?></style>
<body>
<?php $this->beginBody() ?>
<div class="container-fluid"><?= $content ?></div>
<?php $this->endBody() ?>
</body>
<!--</html>-->
<?php $this->endPage() ?>

