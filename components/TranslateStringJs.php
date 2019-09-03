<?php

namespace app\components;

use yii\base\Component;
use Yii;

class TranslateStringJs extends Component {


    public static function translateJs()
    {
        $stringJs=[
            'mapTitle' =>Yii::t('app', 'Geographic location of participants'),
             'Geographic location of participants2',
          ];
    }
}
