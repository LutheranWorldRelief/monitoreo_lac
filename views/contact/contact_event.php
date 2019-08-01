<?php

use app\components\WGridView;
use app\components\WMenuExport;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Contact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title .= ' / Contacto / Event - Contact';
$gridColumns = [
    'contact_id',
    'name',
    'country_id',
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
        <?=
        WGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
            'heading' => '<i class="wi wi-rain-mix wi-flip-horizontal"></i> Contactos por evento',
            'toolbar' => [
                '{toggleData}',
                '{export}',
            ],
        ]);
        ?>
    </div>
</div>
