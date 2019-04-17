<?php

namespace app\components;

use yii\base\Component;

class UForm extends Component {

    public static function FieldOption($opciones = []) {
        $fieldOption = [
            'hintType' => \kartik\form\ActiveField::HINT_SPECIAL,
            'hintSettings' => [
                'placement' => 'right', 'onLabelClick' => true, 'onLabelHover' => false,
                'title' => '<i class="glyphicon glyphicon-info-sign text-info"></i><span class="text-info"> Nota</span>'
            ],
            'feedbackIcon' => [
                'default' => '',
                'success' => 'ok',
                'error' => 'exclamation-sign',
                'defaultOptions' => ['class' => 'text-primary']
            ]
        ];
        return array_merge($fieldOption, $opciones);
    }

    public static function BotonesWizard() {
        return [
            'prev' => ['title' => 'Anterior', 'options' => [ 'class' => 'btn btn-default', 'type' => 'button', 'ng-click' => 'cambioTab()']],
            'next' => ['title' => 'Siguiente', 'options' => [ 'class' => 'btn btn-primary', 'type' => 'button', 'ng-click' => 'cambioTab()']],
            'save' => ['title' => 'Finalizar', 'options' => [ 'class' => 'btn btn-primary', 'type' => 'button']],
            'skip' => ['title' => 'Saltar', 'options' => [ 'class' => 'btn btn-default', 'type' => 'button']],
        ];
    }

}
