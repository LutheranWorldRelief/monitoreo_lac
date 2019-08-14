<?php

use yii\bootstrap\Html;
use yii\grid\GridView;

?>
<div class="col-lg-12">
    <div class="box">
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
                            'style' => 'width:50px',
                        ]
                    ],
                    [
                        'attribute' => 'id',
                        'headerOptions' => [
                            'style' => 'width:50px',
                        ]
                    ],
                    [
                        'header' => Yii::t('app', 'Attendance'),
                        'attribute' => 'fullname',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->fullname, ['contact/view', 'id' => $model->contact_id]);
                        },
                    ],
                    [
                        'header' => Yii::t('app','Document'),
                        'attribute' => 'document'
                    ],
                    [
                        'header' => Yii::t('app','Sex'),
                        'attribute' => 'sex'
                    ],
                    [
                        'header' => Yii::t('app', 'Organization'),
                        'attribute' => 'org_name'
                    ],
                    [
                        'header' => Yii::t('app', 'Country'),
                        'attribute' => 'country_id'
                    ],
                    [
                        'header' => Yii::t('app', 'Community'),
                        'attribute' => 'community'
                    ],
                    [
                        'header' => Yii::t('app', 'Tipo'),
                        'attribute' => 'type_name'
                    ],
                    [
                        'header' => Yii::t('app', 'Phone'),
                        'attribute' => 'phone_personal',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
