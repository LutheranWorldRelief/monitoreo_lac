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
class AngularAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "js/lib/angular.min.js",
//        "js/lib/angular-locale_es-ni.js",
        "js/lib/ui-bootstrap-tpls-0.10.0.min.js",
        "js/lib/angular-resource.min.js",
        "js/lib/angular-sanitize.min.js",
        "js/lib/dirPagination.js",
        "js/servicios_angular/datos.js",
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];

}
