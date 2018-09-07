<?php
/**
 * Clase personalizada para la generación de vistas de los controladores de YII2
 */
namespace app\components;

use app\models\User;
use kartik\select2\Select2Asset;
use kartik\widgets\WidgetAsset;
use yii\base\Controller;
use yii\bootstrap\BootstrapAsset;
use yii\web\JqueryAsset;

/**
 * Custom base class for all the views.
 * @package app\components
 *
 * Properties:
 * @property $ctrl Alias of the context attribute to get the view's controller
 */
class View extends \yii\web\View
{
    public $jqueryDependency;

    public $dependencies;

    public function init()
    {
        parent::init();
        $this->jqueryDependency = ['depends' => [JqueryAsset::className()]];

        $this->dependencies = ['depends' =>
            [
                JqueryAsset::className(),
                Select2Asset::className(),
                WidgetAsset::className(),
                BootstrapAsset::className()
            ]];
    }

    /**
     * Alias of the context attribute to get the view's controller
     * @return Controller
     */
    public function getCtrl()
    {
        return $this->context;
    }
}