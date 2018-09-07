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
class AngularCrudAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "js/crud/crud.js",
        "js/crud/app.js",
        "js/servicios_angular/directivas.js",
    ];
    public $depends = [
        'app\assets\AngularAsset',
        'app\assets\AlertifyAsset',
        'yii\jui\JuiAsset'
    ];

}
