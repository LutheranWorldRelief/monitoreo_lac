<?php
/**
 * Created by PhpStorm.
 * User: yaroslav
 * Date: 08.05.2015
 * Time: 12:35
 */

namespace app\components\excel\import;

use kartik\widgets\FileInput;
use yii\base\Widget;
use yii\helpers\Html;

class ImportFileWidget extends Widget
{

    public $model;
    public $form;
    public $label = null;
    public $options = [];

    public function run()
    {
        return FileInput::widget([
            'name' => ImportBehavior::XLS_FILE,
            'options' => [
                'multiple' => false,
                'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/comma-separated-values'
            ],
            'pluginOptions' => [
                'previewFileType' => 'image',
                'showUpload' => false,
                'overwriteInitial' => true,
                'allowedExtensions' => ['xlsx', 'xls'],
            ]
        ]);
//        return Html::fileInput(ImportBehavior::XLS_FILE);
    }
}