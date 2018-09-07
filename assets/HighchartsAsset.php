<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2016 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Néstor <floresnestormg@gmail.com>
 * @since 1.0
 */
class HighchartsAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web/js/lib/highcharts';
    public $js = [
        "maparequisito/proj4.js",
        "highcharts.js",
        "modules/data.js",
        "modules/drilldown.js",
        "modules/treemap.js",
        "modules/heatmap.js",
        "modules/exporting.js",
        "modules/export-data.js",
        "modules/offline-exporting.js",
        "modules/map.js",
        "mapdata/custom/world.js",

    ];
    public $depends = [
        'app\assets\AppAsset',
        '\yii\web\JqueryAsset',
    ];

}
