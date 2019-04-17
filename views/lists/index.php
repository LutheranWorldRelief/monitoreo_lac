<?php

use kartik\grid\GridView;
use yii\helpers\Html;

echo $this->render('navbar');
?>
<div class="box">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'id' => 'grid-data-list',
            'dataProvider' => $provider,
            'filterModel' => $search,
            'columns' => [
                'id',
                'slug',
                [
                    'attribute' => 'description',
                    'format' => 'raw',
                    'value' => function ($m) {
                        return Html::a(
                            $m->description, ['view', 'id' => $m->id]
                        );
                    },
                ],
                'value',
                'tag',
                'notes',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => false,
                    'deleteOptions' => ['data-pjax' => '#grid-data-list'],
                    'template' => '{update} {delete}'
                ],
            ],
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
            ]
        ]);
        ?>
    </div>
</div>