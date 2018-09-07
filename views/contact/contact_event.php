<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\components\UCatalogo;
use app\models\Organization;
use app\models\Attendeetype;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Contact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title .= ' / Contacto / Event - Contact';
$gridColumns = [
    'contact_id',
    'name',
    'country',
    'org_name',
    'type_name',
    'event',
    'event_id',
    'organizer',
    'start',
    'end',
    'place',

];

?>
<?= $this->render('_navbar') ?>
<div class="box">
    <div class="box-body">
        <?= kartik\export\ExportMenu::widget(['dataProvider' => $dataProvider, 'columns' => $gridColumns]); ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
        ]);
        ?>
    </div>
</div>
