<?php

namespace app\components;

use yii\base\Component;
use yii;

class UExcelBeneficiario extends Component
{
    public static function getCamposPosicion()
    {
        return [
            'proyecto' => 0,
            'organizacion_implementadora' => 1,
            'identificacion' => 2,
            'nombres' => 3,
            'apellidos' => 4,
            'sexo' => 5,
            'nacimiento' => 6,
            'educacion' => 7,
            'telefono' => 8,
            'hombres' => 9,
            'mujeres' => 10,
            'organizacion' => 11,
            'pais' => 12,
            'departamento' => 13,
            'comunidad' => 14,
            'ingreso_proyecto' => 15,
            'rubro' => 16,
            'area' => 17,
            'area_desarrollo' => 18,
            'edad_desarrollo' => 19,
            'area_produccion' => 20,
            'edad_produccion' => 21,
            'rendimiento' => 22,
        ];
    }

    public static function getCamposNombre()
    {
        return [
            'proyecto' => Yii::t('app', 'Nombre de Proyecto'),
            'organizacion_implementadora' => Yii::t('app', 'Organización Implementadora'),
            'identificacion' => Yii::t('app', 'Número de Identificación'),
            'nombres' => Yii::t('app', 'Nombres'),
            'apellidos' => Yii::t('app', 'Apellidos'),
            'sexo' => Yii::t('app', 'Sexo'),
            'nacimiento' => Yii::t('app', 'Fecha de Nacimiento'),
            'educacion' => Yii::t('app', 'Educación'),
            'telefono' => Yii::t('app', 'Teléfono'),
            'hombres' => Yii::t('app', 'Hombres en su familia'),
            'mujeres' => Yii::t('app', 'Mujeres en su familia'),
            'organizacion' => Yii::t('app', 'Organización Perteneciente'),
            'pais' => Yii::t('app', 'País'),
            'departamento' => Yii::t('app', 'Departamento'),
            'comunidad' => Yii::t('app', 'Comunidad'),
            'ingreso_proyecto' => Yii::t('app', 'Fecha de ingreso al proyecto'),
            'rubro' => Yii::t('app', 'Rubro'),
            'area' => Yii::t('app', 'Área de la finca (hectáreas)'),
            'area_desarrollo' => Yii::t('app', 'Área en Desarrollo (hectáreas)'),
            'edad_desarrollo' => Yii::t('app', 'Edad de Plantación en Desarrollo (años)'),
            'area_produccion' => Yii::t('app', 'Área en Producción (hectáras)'),
            'edad_produccion' => Yii::t('app', 'Edad de Plantación en Producción (años)'),
            'rendimiento' => Yii::t('app', 'Rendimientos (qq)'),
        ];
    }

    public static function verificaCamposEnArreglo($arreglo, &$errores)
    {
        if (!is_array($arreglo))
            return false;
        $posicion = self::getCamposPosicion();
        $campos = self::getCamposNombre();
        $valido = true;
        $camposEncontrados = 0;

        foreach ($posicion as $key => $value) {
            $valido = true;
            try {
                $valido &= UString::sustituirEspacios($arreglo[$value], null) == UString::sustituirEspacios(trim($campos[$key]), null);
            } catch (\Exception $exception) {
                return false;
            }
            if (!$valido) {
                $errores[] = Yii::t('app', 'Se esperaba campo "---') . $campos[$key] . Yii::t('app', '---" y se encontró campo "---') . $arreglo[$value] . '---"';
//                return false;
            } else
                $camposEncontrados++;
        }
        $valido &= $camposEncontrados == count($campos);
        return $valido;
    }
}
