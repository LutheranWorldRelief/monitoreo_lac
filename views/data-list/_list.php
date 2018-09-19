<?php  

use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

?>
<div class="col-lg-12">
    <div class="box">
        <div class="box-body">
        <?= GridView::widget([
            'id'=>'grid-details',
            'tableOptions'=>[
                'class'=>'table table-condensed table-stripped',
            ],
            'dataProvider' => $provider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value'=> function ($model) {
                        return Html::a($model->name, "#", [
                                'onclick'=>'loadDetail(event, $(this))',
                                'data-url'=> Url::to(['data-list/find', 'id' => $model->id]),
                                'data-toggle'=> "modal", 
                                'data-target'=> "#detail-modal-edit"
                            ]);
                    }
                ],
                'order',
                'value',
                'notes',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'width' => '150px',
                    'contentOptions'=>[
                        'class'=>'action-column',
                    ],
                    'template' => '{contacts} {update} {delete}',
                    'buttons'=> [
                        'contacts' => function ($url, $model, $key) {
                            return Html::a('<i class="fa fa-user"></i>', ['data-list/view-contacts', 'id' => $model->id], [
                                'target'=>'_blank',
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a('<i class="fa fa-pencil"></i>', "#", [
                                'onclick'=>'loadDetail(event, $(this))',
                                'data-url'=> Url::to(['data-list/find', 'id' => $model->id]),
                                'data-toggle'=> "modal",
                                'data-target'=> "#detail-modal-edit"
                            ]);
                        },
                    ]
                ],
            ],
            'pjax'=>true,
            'pjaxSettings'=>[
            ]
        ]); 
        ?>
        </div>
    </div>
</div>
