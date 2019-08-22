<?php

namespace app\components;
use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguages = [];

    public function bootstrap($app)
    {
        $dataUserLogin = \Yii::$app->user->identity;

        if(isset($dataUserLogin))
            if(in_array($dataUserLogin->language, $this->supportedLanguages))
                \Yii::$app->language = $dataUserLogin->language;
    }
}