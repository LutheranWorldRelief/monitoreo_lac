<?php

namespace app\components;

use Yii;
use yii\base\Component;

class UString extends Component {

    /**
     * @param string $category Category of translation
     * @param string $message Message to be translated
     * @param null $params Array that contains values to replace placeholders in the $message
     * @return string The translated message
     */
    public static function t($category, $message, $params = null)
    {
        return Yii::t($category, $message, $params);
    }

    public static function array2Table($rows){
        $tbody = array_reduce($rows, function($a, $b){return $a.="<tr><td>".implode("</td><td>",$b)."</td></tr>";});
        $thead = "<tr><th>" . implode("</th><th>", array_keys($rows[0])) . "</th></tr>";
        return "<table class='table table-bordered'>\n$thead\n$tbody\n</table>";
    }


    public static function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }

    public static function deletreaCedula($cedula) {
        $n = strlen($cedula);
        $deletrea = '';
        for ($i = 0; $i < $n; $i++) {
            $caracter = $cedula[$i];
            if (is_numeric($caracter)) {
                if ($caracter == 1)
                    $deletrea.='UNO ';
                else
                    $deletrea.=UNumero::Numero2Letras($caracter) . ' ';
            } elseif ($caracter == '-')
                $deletrea.='GUIÓN ';
            elseif ($caracter != ' ')
                $deletrea.='LETRA ' . self::UpperCase($caracter);
        }
        return $deletrea;
    }

    public static function EncodingUTF8($x) {

        if (mb_detect_encoding($x) == 'utf-8')
            return utf8_decode($x);
        else
            return utf8_encode($x);
        /**/

        //return mb_detect_encoding($x);
    }

    public static function arrayToString($value, $glue = ',') {
        if (!$value || !is_array($value) || (count($value) <= 0))
            return '';
        return implode($glue, $value);
    }

    public static function Arreglo_to_String($json, $abreviar_meses = false) {
        $result = '';

        if ($abreviar_meses) {
            $json = self::getAbreviaArrayMeses($json);
        }

        if ($json) {
            for ($i = 0; $i < count($json); $i++) {
                $result = $result . $json[$i];
                if ($i == (count($json) - 1)) {
                    $result = $result . '. ';
                } else
                    $result = $result . ', ';
            }
        }

        return $result;
    }

    public static function Arreglo_to_String_List($json, $ordenado = false, $class = null) {
        $abre = '<ul class="' . $class . '">';
        $cierra = '</ul>';

        if ($ordenado) {
            $abre = '<ol class="' . $class . '">';
            $cierra = '</ol>';
        }

        $result = $abre;
        if ($json) {

            for ($i = 0; $i < count($json); $i++) {
                $result .= "<li> " . $json[$i] . "</li>";
            }
        }

        $result .= $cierra;
        return $result;
    }

    public static function toUpper($post) {

        $data = array();

        foreach ($post as $key => $value)
            if (is_string($value))
                $data[$key] = self::UpperCase($value);

        return $data;
    }

    public static function toLower($post) {

        $data = array();

        foreach ($post as $key => $value)
            if (is_string($value))
                $data[$key] = self::lowerCase($value);

        return $data;
    }

    public static function upperCase($string) {
        return mb_strtoupper($string, 'UTF-8');
    }

    public static function lowerCase($string) {
        return mb_strtolower($string, 'UTF-8');
    }

    public static function quitarAcentos($cadena) {
        $originales  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);
        return utf8_encode($cadena);
    }

    public static function sustituirEspacios($cadena, $sustituir_con = '_') {
        return preg_replace('/\s+/', $sustituir_con, $cadena);
    }

}
