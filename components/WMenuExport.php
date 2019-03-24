<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use kartik\export\ExportMenu;
use yii\base\Widget;

class WMenuExport extends Widget
{

    public $dataProvider;
    public $columns;
    public $filename = 'Cafenica';
    private $opciones = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->setOpciones();
        return ExportMenu::widget($this->opciones);
    }

    private function setOpciones()
    {
        $this->opciones = [
            'dataProvider' => $this->dataProvider,
            'columns' => $this->columns,
            'target' => ExportMenu::TARGET_BLANK,
            'fontAwesome' => true,
            'filename' => $this->filename . '_' . date('Ymd_Hi'),
            'showColumnSelector' => false,
            'showConfirmAlert' => false,
            'pjaxContainerId' => 'kv-pjax-container',
            'dropdownOptions' => ['label' => 'Exportar Todo', 'style' => 'color:white', 'class' => 'btn btn-primary  waves-effect waves-light', 'itemsBefore' => ['<li class="dropdown-header">Exportar Todo</li>'],],
        ];
    }

}
