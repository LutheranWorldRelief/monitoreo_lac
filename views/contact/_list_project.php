<?php  

use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

?>
<div class="col-lg-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Projects</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>

            </div>
        </div>
        <div class="box-body">
        <?= GridView::widget([
            'id'=>'grid-details',
            'tableOptions'=>[
                'class'=>'table table-condensed table-stripped',
            ],
            'dataProvider' => $provider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions'=>[
                        'style'=>'width:30px',
                    ]
                ],
                [
                     'attribute'=>'id',
                     'headerOptions'=>[
                         'style'=>'width:30px',
                     ]
                ],
                [
                     'header'=>'Project',
                     'attribute'=>'name',
                     'format'=>'raw',
                     'value'=> function ($model) {
                         return Html::a($model->name, ['project/view', 'id'=>$model->id], ['target'=>'_blank']);
                     },
                ],
            ]
        ]); 
        ?>
        </div>
    </div>
</div>
