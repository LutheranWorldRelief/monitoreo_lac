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
class AngularTreeAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "js/servicios_angular/tree-grid-directive.js",
    ];
    public $css = [
        "js/servicios_angular/treeGrid.css",
    ];
    public $depends = [
        'app\assets\AngularAsset',
    ];

}
