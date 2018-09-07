<?php

namespace app\components;

use yii\base\Component;

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
            'proyecto' => 'Nombre de Proyecto',
            'organizacion_implementadora' => 'Organización Implementadora',
            'identificacion' => 'Número de Identificación',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'sexo' => 'Sexo',
            'nacimiento' => 'Fecha de Nacimiento',
            'educacion' => 'Educación',
            'telefono' => 'Teléfono',
            'hombres' => 'Hombres en su familia',
            'mujeres' => 'Mujeres en su familia',
            'organizacion' => 'Organización Perteneciente',
            'pais' => 'País',
            'departamento' => 'Departamento',
            'comunidad' => 'Comunidad',
            'ingreso_proyecto' => 'Fecha de ingreso al proyecto',
            'rubro' => 'Rubro',
            'area' => 'Área de la finca (hectáreas)',
            'area_desarrollo' => 'Área en Desarrollo (hectáreas)',
            'edad_desarrollo' => 'Edad de Plantación en Desarrollo (años)',
            'area_produccion' => 'Área en Producción (hectáras)',
            'edad_produccion' => 'Edad de Plantación en Producción (años)',
            'rendimiento' => 'Rendimientos (qq)',
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
                $errores[] = 'Se esperaba campo "---' . $campos[$key] . '---" y se encontró campo "---' . $arreglo[$value] . '---"';
//                return false;
            } else
                $camposEncontrados++;
        }
        $valido &= $camposEncontrados == count($campos);
        return $valido;
    }
}