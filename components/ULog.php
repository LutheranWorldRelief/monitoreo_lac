<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;

class ULog extends Component {

    public static function Log($data, $exit = true, $depth = 10, $highlight = true) {
        VarDumper::dump($data, $depth, $highlight);
        if ($exit)
            \Yii::$app->end();
    }

    public static function l($data, $exit = true, $depth = 10, $highlight = true) {
    	return self::Log($data, $exit, $depth, $highlight);
    }

}
