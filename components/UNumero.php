<?php

namespace app\components;

use yii\base\Component;

class UNumero extends Component {

    public static function FormatoNumero($num, $decimales = 2) {

        $num = 0 + $num;

        if ($num >= 0)
            return number_format($num, $decimales, ".", ",");

        $number = number_format($num, $decimales, ".", ",");
        $numero = explode('-', $number);
        return '(' . $numero[1] . ')';
    } 

}
