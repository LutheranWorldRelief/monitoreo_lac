<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class UFechaHora extends Component {

    public static function today() {

        return date('Y-m-d');
    }

    public static function formatTime($time, $military = false) {

        $data = date("h:i:s A", strtotime($time));

        if ($military)
            $data = date("H:i:s A", strtotime($time));

        return $data;
    }

    // return: 01 ene, 2014
    public static function medium($date) {

        setlocale(LC_TIME, 'spanish');
        return strftime("%d %b, %Y", strtotime($date));
    }

    //return: lunes, primero de enero del año dos mil catorce
    public static function FechaLetrasESFullText($date) {

        setlocale(LC_TIME, 'spanish');
        $diaS = strftime("%A, ", strtotime($date));
        $_dia = strftime("%#d", strtotime($date));
        $mes = strftime("%B", strtotime($date));
        $_anio = strftime("%Y", strtotime($date));

        $dia = UString::lowerCase(UNumero::Numero2Letras($_dia));
        $anio = UString::lowerCase(UNumero::Numero2Letras($_anio));
        $fecha = utf8_encode($diaS) . $dia . " de " . $mes . " del año " . $anio;
        return $fecha;
    }

    public static function MesFechaLetrasESText($date) {

        setlocale(LC_TIME, 'spanish');

        $mes = strftime("%b", strtotime($date));
        $anio = strftime("%y", strtotime($date));

        $fecha = ucwords($mes) . "-" . $anio;
        return $fecha;
    }

    // return: lunes, 01 de enero del 2014
    public static function FechaLetrasES($date) {

        setlocale(LC_TIME, 'spanish');
        $diaS = strftime("%A, ", strtotime($date));

        $fecha = utf8_encode($diaS) . strftime("%#d de %B del %Y", strtotime($date));
        return $fecha;
    }

    // return: 01 de enero del 2014
    public static function FechaLetras($date) {

        setlocale(LC_TIME, 'spanish');

        $fecha = strftime("%#d de %B del %Y", strtotime($date));
        return $fecha;
    }

    public static function diferenciaEntreFechasDias($fecha1, $fecha2) {

        $f1 = date_create($fecha1);
        $f2 = date_create($fecha2);

        $dias = 0;
        if ($f1 < $f2) {
            $intervalo = date_diff($f2, $f1);
            $dias = $intervalo->days;
        }

        return $dias;
    }

    public static function dateDiff($start, $end) {

        $start_ts = strtotime($start);

        $end_ts = strtotime($end);

        // 86400 segundos en un día
        return round(($end_ts - $start_ts) / 86400);
    }

    //función para obtener los días de un mes
    public static function DiasEnMes($anio, $mes) {
        return date("t", mktime(0, 0, 0, $mes, 1, $anio));
    }

    //FechaSiguienteAnioMesDia
    public static function FechaSiguienteAnioMesDia($anio, $mes, $dia) {
        return Utils::FechaSiguienteTime(mktime(0, 0, 0, $mes, $dia, $anio));
    }

    //FechaSiguienteFecha
    public static function FechaSiguienteFecha($fecha) {
        return Utils::FechaSiguienteTime(strtotime($fecha));
    }

    //FechaSiguienteTime
    public static function FechaSiguienteTime($time) {
        return date("Y-m-d", $time + 86400);
    }

    //FechaAnteriorAnioMesDia
    public static function FechaAnteriorAnioMesDia($anio, $mes, $dia) {
        return Utils::FechaAnteriorTime(mktime(0, 0, 0, $mes, $dia, $anio));
    }

    //FechaAnteriorFecha
    //función para obtener los días de un mes
    public static function FechaAnteriorFecha($fecha) {
        return Utils::FechaAnteriorTime(strtotime($fecha));
    }

    //FechaAnteriorTime
    public static function FechaAnteriorTime($time) {
        return date("Y-m-d", $time - 86400);
    }

    //DiasEnMes
    public static function AnioDiaMesAFechaFormat($anio, $mes, $dia) {

        return date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $anio));
    }

    public static function FechaFormatoNicaragua($fecha) {
        return self::TimeFormatoNicaragua(strtotime($fecha));
    }

    public static function TimeFormatoNicaragua($time) {

        if ($time)
            return date("d/m/Y", $time);

        return "";
    }

    public static function getNombreMesesArray() {

        return array(
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        );
    }

    public static function getValuesMesesArray($valor = 0, $meses = 12) {

        $result = array();
        for ($i = 0; $i < $meses; $i++)
            $result[$i] = $valor;

        return $result;
    }

    public static function getNumberWeekRoman() {

        return array(
            1 => "I",
            2 => "II",
            3 => "III",
            4 => "IV",
            5 => "V",
            6 => "VI",
            7 => "VII",
            8 => "VIII",
            9 => "IX",
            10 => "X",
        );
    }

    public static function getAbrevMesesArray() {

        // date("M", mktime(0, 0, 0, $model->semana, 10));

        return array(
            1 => "Ene",
            2 => "Feb",
            3 => "Mar",
            4 => "Abr",
            5 => "May",
            6 => "Jun",
            7 => "Jul",
            8 => "Ago",
            9 => "Sep",
            10 => "Oct",
            11 => "Nov",
            12 => "Dic"
        );
    }

    public static function getMesAbrv($num) {

        if ($num > 12 || $num < 1)
            return "";

        $meses = self::getAbrevMesesArray();

        return $meses[$num];
    }

    public static function getMesNombre($num) {

        if ($num > 12 || $num < 1)
            return "";

        $meses = self::getNombreMesesArray();

        return $meses[$num];
    }

    public static function getMesNombreAbrev($num) {

        if ($num > 12 || $num < 1)
            return "";

        $meses = self::getAbrevMesesArray();

        return $meses[$num];
    }

    public static function getAbreviaArrayMeses($mes) {
        $abreviados = array();
        for ($i = 1; $i <= count($mes); $i++) {
            $m = $mes[$i];
            switch ($m) {
                case "Enero": {
                        $abreviados[] = "Ene";
                        break;
                    }
                case "Febrero": {
                        $abreviados[] = "Feb";
                        break;
                    }
                case "Marzo": {
                        $abreviados[] = "Mar";
                        break;
                    }
                case "Abril": {
                        $abreviados[] = "Abr";
                        break;
                    }
                case "Mayo": {
                        $abreviados[] = "May";
                        break;
                    }
                case "Junio": {
                        $abreviados[] = "Jun";
                        break;
                    }
                case "Julio": {
                        $abreviados[] = "Jul";
                        break;
                    }
                case "Agosto": {
                        $abreviados[] = "Ago";
                        break;
                    }
                case "Septiembre": {
                        $abreviados[] = "Sep";
                        break;
                    }
                case "Octubre": {
                        $abreviados[] = "Oct";
                        break;
                    }
                case "Noviembre": {
                        $abreviados[] = "Nov";
                        break;
                    }
                case "Diciembre": {
                        $abreviados[] = "Dic";
                        break;
                    }
                default : {
                        $abreviados[] = $m;
                        break;
                    }
            }
        }

        return $abreviados;
    }

    public static function getAniosRangos($ini, $fin = null) {

        if (!$fin)
            $fin = date("Y");

        $anios = array();
        for ($i = $ini; $i <= $fin; $i++)
            $anios[$i] = $i;

        return $anios;
    }

    public static function Hora2Letras($hora = '10;00;AM') {
        $time = explode(";", $hora);
        $horas = self::Numero2Letras($time[0]);
        $minutos = self::Numero2Letras($time[1]);
        $meridiano = null;
        if ($time[2] == 'AM')
            $meridiano = 'MAÑANA';
        else
            $meridiano = 'TARDE';

        if ($minutos == 'CERO ') {//dejar el espacio porque asi lo retorna
            return $horas . " EN PUNTO DE LA " . $meridiano;
        }
        return $horas . " Y " . $minutos . " DE LA " . $meridiano;
    }

    /** Actual month last day * */
    public static function ultimoDiaMes($month = null, $year = null) {
        if ($month == null)
            $month = date('m');
        if ($year == null)
            $year = date('Y');
        $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));

        return date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
    }

    /** Actual month first day * */
    public static function primerDiaMes($month = null, $year = null) {
        if ($month == null)
            $month = date('m');
        if ($year == null)
            $year = date('Y');
        return date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
    }

}
