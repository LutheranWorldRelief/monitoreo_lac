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
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions'=>[
                        'style'=>'width:50px',
                    ]
                ],
                [
                    'attribute'=>'id',
                    'headerOptions'=>[
                        'style'=>'width:50px',
                    ]
                ],
                [
                    'header'=>'Attendance',
                    'attribute'=>'fullname',
                    'format'=>'raw',
                    'value'=> function ($model) {
                        return Html::a($model->fullname, ['contact/view', 'id'=>$model->id], ['data-pjax'=>'']);
                    },
                ],
                [
                    'header'=>'Document',
                    'attribute'=>'document'
                ],
                [
                    'header'=>'Sex',
                    'attribute'=>'sex'
                ],
                [
                    'header'=>'Country',
                    'attribute'=>'countryName'
                ],
                [
                    'header'=>'Community',
                    'attribute'=>'community'
                ],
                [
                    'header'=>'Tipo',
                    'attribute'=>'attendeeTypeName'
                ],
                [
                    'header'=>'Phone',
                    'attribute'=>'phone_personal',
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
