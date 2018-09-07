<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;

class UArchivos extends Component {

    public static function getContentFile($file) {
        $cssFile = empty($file) ? '' : Yii::getAlias($file);

        if (empty($cssFile) || !file_exists($cssFile))
            $css = '';
        else
            $css = file_get_contents($cssFile);
        return $css;
    }

}
