<?php  

use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

?>
<div class="col-lg-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Events</h3>

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
                // [
                //     'attribute'=>'id',
                //     'headerOptions'=>[
                //         'style'=>'width:30px',
                //     ]
                // ],
                [
                    'attribute'=>'start',
                    'headerOptions'=>[
                        'style'=>'width:100px',
                    ],
                    'value'=>function($model){
                        return substr($model->start, 0, 10);
                    }
                ],
                [
                    'header'=>'Events',
                    'attribute'=>'name',
                    'format'=>'raw',
                    'value'=> function ($model) {
                        return Html::a($model->name, ['event/view', 'id'=>$model->id], ['target'=>'_blank']);
                    },
                ],
                [
                    'header'=>'Project',
                    'attribute'=>'project_name',
                    'format'=>'raw',
                    'value'=> function ($model) {
                        return Html::a($model->project_name, ['project/view', 'id'=>$model->project_id], ['target'=>'_blank']);
                    },
                ],
                [
                    'attribute'=>'place',
                    'headerOptions'=>[
                        'style'=>'width:200px',
                    ],
                    'value'=>function($model){
                        if (strlen($model->place) <= 15)
                            return $model->place;
                        return substr($model->place, 0, 15) . "...";
                    }
                ],
            ],
            'pjax'=>false,
            'pjaxSettings'=>[
            ]
        ]); 
        ?>
        </div>
    </div>
</div>
