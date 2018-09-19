<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\DataList;
use app\assets\AlertifyAsset;

/* @var $this yii\web\View */
/* @var $model app\models\DataList */
AlertifyAsset::register($this);

$this->registerJsFile('@web/js/vue/vue2.js');
$this->registerJsFile('@web/js/datalist.view.vue.js', ['depends'=>[
    'app\assets\AppAsset',
    'yii\web\JqueryAsset',
    'yii\web\YiiAsset',
]]);

$this->title .= ' / DataList / ' . $model->name . ' / Contactos';
echo $this->render('_navbar', [
    'options' => [
        [
            'label' => '<i class="fa fa-reply"></i> Regresar',
            'url' => ['data-list/view', 'id' => $model->id],
            'encode'=>false
        ],
    ]
])
?>
<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $contacts
    ]),
    'columns' => [
        'id',
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a($model->name, ['contact/view', 'id' => $model->id], ['target'=>'_blank']);
            },
        ],
        'countryName',
        'organizationName',
        'educationName',
        'typeName',
    ],
]) ?>
