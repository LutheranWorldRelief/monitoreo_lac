<?php

use kartik\grid\GridView;
use yii\bootstrap\Html;

?>
<div class="col-lg-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Activities</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>

            </div>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'id' => 'grid-details',
                'tableOptions' => [
                    'class' => 'table table-condensed table-stripped',
                ],
                'dataProvider' => $provider,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => [
                            'style' => 'width:30px',
                        ]
                    ],
                    [
                        'attribute' => 'id',
                        'headerOptions' => [
                            'style' => 'width:30px',
                        ]
                    ],
                    [
                        'header' => 'Activity',
                        'attribute' => 'description',
                    ],
                    [
                        'header' => 'Project',
                        'attribute' => 'project_name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->project_name, ['project/view', 'id' => $model->project_id], ['target' => '_blank']);
                        },
                    ],
                ]
            ]);
            ?>
        </div>
    </div>
</div>
