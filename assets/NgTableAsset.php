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
class NgTableAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "css/jquery.ui.theme.css",
        "js/lib/ng-table/ng-table.min.css",
    ];
    public $js = [
        "js/lib/ng-table/ng-table.min.js",
        "js/lib/ng-table/ng-table-export.js",
        "js/servicios_angular/ng_table.js",
        "js/servicios_angular/ng_table_export.js",
        "js/servicios_angular/directivas.js",
    ];
    public $depends = [
        'app\assets\AngularAsset',
        'app\assets\AlertifyAsset',
        'yii\jui\JuiAsset'
    ];

}
