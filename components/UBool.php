<?php

namespace app\components;

use yii\base\Component;

class UBool extends Component
{

    public static function Str2Bool($val)
    {
        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
    }

}
