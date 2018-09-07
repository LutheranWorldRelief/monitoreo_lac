<?php

use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>
<?= $this->render('/menu/_menu') ?>
<div class="box">
    <div class="box-body">
        <div class="role-index">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'header' => 'Nombre',
                        'label' => Yii::t('rbac-admin', 'Name'),
                    ],
//                    [
//                        'attribute' => 'ruleName',
//                        'label' => Yii::t('rbac-admin', 'Rule Name'),
//                        'filter' => $rules
//                    ],
                    [
                        'attribute' => 'description',
                        'header' => 'Descripción',
                        'label' => Yii::t('rbac-admin', 'Description'),
                    ],
                    ['class' => 'yii\grid\ActionColumn',],
                ],
            ])
            ?>

        </div>
    </div>
</div>
