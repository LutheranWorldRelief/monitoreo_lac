<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use kartik\grid\GridView;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class WGridView extends Widget
{

    public $dataProvider;
    public $filterModel;
    public $columns;
    public $opciones = [];
    public $type = GridView::TYPE_INFO;
    public $heading = null;
    public $toolbar = ['{export}', '{toggleData}'];
    public $before = null;
    public $beforeShow = true;
    public $after = false;
    public $pjax = false;

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
        return GridView::widget($this->opciones);
    }

    private function setOpciones()
    {
        $this->opciones = [
            'dataProvider' => $this->dataProvider,
            'filterModel' => $this->filterModel,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'responsiveWrap' => false,
            'hover' => true,
            'resizableColumns' => true,
            'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
            'columns' => $this->columns,
            'toolbar' => $this->toolbar,
            'pjax' => $this->pjax,
            'export' => [ 
                'label' => 'Exportar',
                'showConfirmAlert' => false,
                ],
            'panel' => [
                'type' => $this->type,
                'heading' => $this->heading,
                'footerOptions' => [
                    'style' => 'background:white'
                ],
                'before' => $this->beforeShow ? Html::a('<i class="fa fa-refresh"></i> Recargar Grid', Url::current(), ['class' => 'btn btn-info']) . $this->before : null,
                'after' => $this->after,
            ],

        ];
    }


}
