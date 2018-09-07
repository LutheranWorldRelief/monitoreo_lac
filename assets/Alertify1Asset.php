<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Alertify1Asset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "js/lib/alertify1.11.1/css/alertify.css",
    ];
    public $js = [
        "js/lib/alertify1.11.1/alertify.min.js",
    ];
    public $depends = [
        'app\assets\AppAsset',
        'yii\web\JqueryAsset',
    ];

}
