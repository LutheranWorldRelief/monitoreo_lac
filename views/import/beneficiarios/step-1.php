<?php
//\app\components\ULog::l($data['valido']);
extract($data);

use app\components\excel\import\ImportFileWidget;
use app\components\UExcelBeneficiario;
use yii\helpers\Url; ?>
<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <h3 class="text-info pull-left">Paso 1: Cargar el Archivo</h3>
        <a href="<?= Url::to(['report/template-clean/']) ?>"
           class="btn btn-info pull-right"><i class="fa fa-download"></i> Descargar Plantilla</a>
    </div>
</div>
<?php if (!$valido): ?>
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <h5 class="alert alert-danger">El Archivo no cumple con el formato esperado</h5>

        </div>

    </div>

    <?php if (count($errores) < 4): foreach ($errores as $e): ?>

        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <h5 class="alert alert-danger"><?= $e ?></h5>
            </div>

        </div>

    <?php endforeach; endif; ?>
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            El archivo debe tener las siguientes columnas en el siguiente orden
            <a href="<?= Url::to(['report/template-clean/']) ?>"
               class="btn btn-info"><i class="fa fa-download"></i> Descargar Plantilla</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <?php foreach (UExcelBeneficiario::getCamposNombre() as $campo): ?>
                        <td><b><?= $campo ?></b></td>
                    <?php endforeach; ?>
                </tr>
                </thead>
            </table>
        </div>
    </div>


    <br>
    <br>
<?php endif; ?>

<div class="row">

    <div class="col-lg-10 col-lg-offset-1 well">
        <label for="excel_file" class="control-label">
            <span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;
                                                                  Archivo de beneficiarios
        </label>
        <?= ImportFileWidget::widget() ?>

    </div>
</div>
<br>

<ul class="list-inline pull-right">
    <li>
        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-upload fa-w-16"></i>
            Importar y continuar
        </button>
    </li>
</ul>
<div style="clear: both;"></div>
</div>


