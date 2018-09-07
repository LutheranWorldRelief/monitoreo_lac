<?php

use yii\widgets\DetailView;

?>

<div class="row">
    <div class="col-md-4 col-xs-12">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'username',
                'last_login',
                'activo',
                'superUsuario',
            ],
        ])
        ?>
    </div>
    <div class="col-md-4 col-xs-12">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'first_name',
                'last_name',
                'email',
            ],
        ])
        ?>
    </div>

</div>