<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;

class ULibreriasJsCss extends Component {

    public static function RegistrarSelect2($view) {
        $view->registerJsFile("@web/js/lib/select2/js/select2.js", ['position' => \yii\web\View::POS_HEAD, 'depends' => [yii\web\JqueryAsset::className()]]);
        $view->registerJsFile("@web/js/lib/select2/js/i18n/es.js", []);
        $view->registerCssFile("@web/js/lib/select2/css/select2.css", []);
        $view->registerCssFile("@web/js/lib/select2/css/select2-addl.css", []);
        $view->registerCssFile("@web/js/lib/select2/css/select2-krajee.css", []);
        $view->registerCssFile("@web/js/lib/select2/css/select2-bootstrap.min.css", []);
    }

}
