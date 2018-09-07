<?php

use yii\helpers\Html;
$archivo = Yii::$app->request->get('archivo');
extract($data);
?>
<div class="row">
    <div class="col-lg-4 col-lg-offset-1">
        <h3 class="text-info pull-left">Proceso Finalizado con éxito</h3>
    </div>
    <div class="col-lg-7">
        <ul class="list-inline pull-right">
            <li>
                <a class="btn btn-success btn-block pull-right" href="<?= \yii\helpers\Url::to(['import/beneficiarios-paso4', 'archivo' => $archivo]) ?>">
                    <i class="fa fa-check"></i> Finalizar y ver duplicados
                </a>
            </li>

        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 table-responsive">
        <h3>Eventos Creados</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Evento</th>
                <th>Proyecto</th>
                <th>Actividad</th>
                <th>Organización Implementadora</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $d): ?>
                <tr>
                    <td><?= Html::a($d['name'], \yii\helpers\Url::to(['event/view', 'id' => $d['id']]), ['target' => '_blank']) ?></td>
                    <td><?= $d['structure']['project']['name'] ?></td>
                    <td><?= $d['structure']['description'] ?></td>
                    <td><?= $d['implementingOrganization']['name'] ?></td>
                    <td><?= $d['start'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div style="clear: both;"></div>
</div>

