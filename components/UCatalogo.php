<?php

namespace app\components;

use app\models\DataList;
use yii\base\Component;

class UCatalogo extends Component
{

    public static function listSiNo()
    {
        return [0 => "No", 1 => "Si",];
    }


    public static function CountryCode($name)
    {
        $keys = array_keys(self::listCountries(), $name);
        if (count($keys) > 0)
            return $keys[0];
        return null;
    }

    public static function listCountries()
    {

        return DataList::itemsBySlug('countries');
    }

    public static function listVariedadesCafe()
    {
        return [
            "Catuai" => "Catuaí",
            "Catuai rojo" => "Catuaí rojo",
            "Caturra" => "Caturra",
            "Caturron" => "Caturrón",
            "Catrenic" => "Catrenic",
            "CR95" => "CR95",
            "Bourbon" => "Bourbon",
            "Icatu" => "Icatú",
            "Java" => "Java",
            "Lempira" => "Lempira",
            "Marsellesa" => "Marsellesa",
            "Parainema" => "Parainema",
            "Pacas" => "Pacas",
        ];
    }

    public static function listTipoUsuarios()
    {
        return [1 => "Administrador", 2 => "Productor", 3 => "Técnico"];
    }

    public static function listParcelas()
    {
        return self::ValueEqualKey([
            'Manejo',
            'Variedad',
        ]);
    }

    public static function listTecnologias()
    {
        return self::ValueEqualKey([
            'Convencional',
            'Orgánico',
        ]);
    }

    public static function listSexos()
    {
        return ["M" => "Masculino", "F" => "Femenino",];
    }

    public static function getSiNo($valor)
    {
        return $valor == 1 ? 'Si' : 'No';
    }

    public static function getSexo($valor)
    {
        return $valor == "masculino" ? "Masculino" : 'Femenino';
    }

    public static function getSexoCorto($valor)
    {
        return $valor == "masculino" ? "M" : 'F';
    }

    public static function Meses($opcional = false)
    {
        return self::ValueEqualKey([
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ], $opcional);
    }

    public static function getNombreMesesArrayDesde1()
    {

        return [
            1 => Yii::t('app', "Enero"),
            2 => Yii::t('app', "Febrero"),
            3 => Yii::t('app', "Marzo"),
            4 => Yii::t('app', "Abril"),
            5 => Yii::t('app', "Mayo"),
            6 => Yii::t('app', "Junio"),
            7 => Yii::t('app', "Julio"),
            8 => Yii::t('app', "Agosto"),
            9 => Yii::t('app', "Septiembre"),
            10 => Yii::t('app', "Octubre"),
            11 => Yii::t('app', "Noviembre"),
            12 => Yii::t('app', "Diciembre")
        ];
    }

    public static function Opcional($array, $opcional = false)
    {

        if ($opcional)
            return array_merge(array("" => " --- "), $array);

        return $array;
    }

    public static function ValueEqualKey($array, $opcional = false)
    {

        $return = array();

        foreach ($array as $value)
            $return[$value] = $value;

        return self::Opcional($return, $opcional);
    }

    public static function TipoCompra($opcional = false)
    {

        return self::Opcional(array(
            0 => 'Crédito',
            1 => 'Contado',
        ), $opcional);
    }

    public static function Estados()
    {
        return [0 => "Inactivo", 1 => "Activo",];
    }

}
