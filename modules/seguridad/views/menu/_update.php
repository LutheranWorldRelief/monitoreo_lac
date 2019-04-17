<?php

use dektrium\user\models\User;
use yii\bootstrap\Nav;

?>
<?=

Nav::widget([
    'options' => [
        'class' => 'nav-pills nav-stacked',
    ],
    'items' => [
        ['label' => Yii::t('user', 'Detalle de la Cuenta'), 'url' => ['/seguridad/usuarios/update', 'id' => $user->id]],
        ['label' => Yii::t('user', 'Detalle del Perfil'), 'url' => ['/seguridad/usuarios/update-profile', 'id' => $user->id]],
        ['label' => Yii::t('user', 'Permisos'), 'url' => ['/seguridad/asignaciones/view', 'id' => $user->id]],
        ['label' => Yii::t('user', 'Información'), 'url' => ['/seguridad/usuarios/info', 'id' => $user->id]],
        [
            'label' => Yii::t('user', 'Block'),
            'url' => ['/seguridad/usuarios/block', 'id' => $user->id],
            'visible' => !$user->isBlocked,
            'linkOptions' => [
                'class' => 'text-danger',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Está seguro de bloquear este usuario?'),
            ],
        ],
        [
            'label' => Yii::t('user', 'Unblock'),
            'url' => ['/seguridad/usuarios/block', 'id' => $user->id],
            'visible' => $user->isBlocked,
            'linkOptions' => [
                'class' => 'text-success',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Está seguro de habilitar este usuario?'),
            ],
        ],
        [
            'label' => Yii::t('user', 'Eliminar'),
            'url' => ['/seguridad/usuarios/delete', 'id' => $user->id],
            'linkOptions' => [
                'class' => 'text-danger',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Está seguro de eliminar este usuario?'),
            ],
        ],
    ],
])
?>