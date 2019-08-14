<?php
$user = Yii::$app->user->identity;
if (!$user)
    $user = new app\models\AuthUser;

function validaArray($array)
{
    $valido = false;
    foreach ($array as $i)
        $valido = $valido || $i;
    return $valido;
}

/* Seguridad */
$SeguridadItems = [];
$SeguridadItems[] = $visibleSeUsuarios = $user->tienePermiso('seguridad/usuarios');
$SeguridadItems[] = $visibleSeRoles = $user->tienePermiso('seguridad/roles');
$SeguridadItems[] = $visibleSeRutas = $user->tienePermiso('seguridad/rutas');
$SeguridadItems[] = $visibleSeBitacora = $user->tienePermiso('audit');

$visibleSeguridad = validaArray($SeguridadItems);
/* Fin Seguridad */

/* Participantes*/
$ParticipantesItems = [];
$ParticipantesItems [] = $visibleParticipantes = $user->tienePermiso('contact/index');
$ParticipantesItems [] = $visibleParticipantesEventos = $user->tienePermiso('contact/contact-event');
$ParticipantesItems [] = $visibleParticipantesEventos = $user->tienePermiso('contact/contact-event');
$ParticipantesItems [] = $visibleDuplicadosNombre = $user->tienePermiso('opt/debug-contact-name');
$ParticipantesItems [] = $visibleDuplicadosDocumento = $user->tienePermiso('opt/debug-contact-doc');
$visibleParticipantesMenu = validaArray($ParticipantesItems);
/* Fin Participantes*/


/* Configuraciones*/
$ReportesItem = [];
$ReportesItem[] = $visibleExcel = $user->tienePermiso('report/');
$ReportesItem [] = $visiblePlantilla = $user->tienePermiso('report/template-clean');

$visibleReportes = validaArray($ReportesItem);

/* Fin Configuraciones*/

/* Configuraciones*/

$ConfigItems = [];
$ConfigItems [] = $visibleProyectos = $user->tienePermiso('project/index');
$ConfigItems [] = $visibleOrganizacion = $user->tienePermiso('organization/index');
$ConfigItems [] = $visibleTipoOrg = $user->tienePermiso('organization-type/index');
//$ConfigItems [] = $visibleProyectos = $user->tienePermiso('project/index');
$ConfigItems [] = $visibleCatalogo = $user->tienePermiso('data-list/index');
$ConfigItems [] = $visibleSegementacion = $user->tienePermiso('filter/index');

$visibleConfig = validaArray($ConfigItems);

/* Fin Configuraciones*/

use dmstr\widgets\Menu; ?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::getAlias('@web/img/logo_user.png') ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->first_name . ' ' . $user->last_name ?></p>
                <small><?= $user->email ?></small>
            </div>
        </div>

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Menú', 'options' => ['class' => 'header']],
                    [
                        'label' => Yii::t('app', 'Importar Beneficiarios'),
                        'icon' => 'upload',
                        'url' => Yii::$app->urlManager->createAbsoluteUrl(['/import/beneficiarios-paso1']),
                        'visible' => $user->tienePermiso('import/beneficiarios-paso1')
                    ],
                    [
                        'label' => Yii::t('app', 'Participantes'),
                        'icon' => 'users',
                        'visible' => $visibleParticipantesMenu,
                        'items' => [
                            ['icon' => 'users', 'label' => Yii::t('app', 'Participantes'), 'url' => Yii::$app->urlManager->createAbsoluteUrl(['/contact/index']), 'visible' => $visibleParticipantes],
                            ['icon' => 'users', 'label' => Yii::t('app', 'Participantes/Evento'), 'url' => Yii::$app->urlManager->createAbsoluteUrl(['/contact/contact-event']), 'visible' => $visibleParticipantesEventos],
                            ['label' => Yii::t('app', 'Duplicados'), 'visible' => $visibleDuplicadosNombre || $visibleDuplicadosDocumento,
                                'items' => [
                                    ['label' => Yii::t('app', 'Nombre'), 'icon' => 'user', 'url' => Yii::$app->urlManager->createAbsoluteUrl(['/opt/debug-contact-name']), $visibleDuplicadosNombre],
                                    ['label' => Yii::t('app', 'Documento'), 'icon' => 'id-card', 'url' => Yii::$app->urlManager->createAbsoluteUrl(['/opt/debug-contact-doc']), $visibleDuplicadosDocumento],
                                ]
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Eventos'),
                        'icon' => 'calendar',
                        'url' => Yii::$app->urlManager->createAbsoluteUrl(['event/index']),
                        'visible' => $user->tienePermiso('event/index')
                    ],
                    ['label' => Yii::t('app', 'Reportes'), 'options' => ['class' => 'header'], 'visible' => $visibleReportes],
                    [
                        'label' => Yii::t('app', 'Reports'),
                        'icon' => 'print',
                        'url' => '#',
                        'visible' => $visibleReportes,
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Principal'),
                                'icon' => 'file-excel-o',
                                'url' => Yii::$app->urlManager->createAbsoluteUrl(['report/index']),
                                'visible' => $visibleExcel,
                            ],
                            [
                                'label' => Yii::t('app', 'Plantilla en Limpio'),
                                'icon' => 'file-excel-o',
                                'url' => Yii::$app->urlManager->createAbsoluteUrl(['report/template-clean']),
                                'visible' => $visiblePlantilla,
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Gráficos'),
                        'icon' => 'bar-chart',
                        'url' => Yii::$app->urlManager->createAbsoluteUrl(['graphic/dashboard']),
                        'visible' => $user->tienePermiso('graphic/dashboard')
                    ],
                    ['label' => Yii::t('app', 'Catálogos y Configuraciones'), 'options' => ['class' => 'header']],

                    [
                        'visible' => $visibleConfig,
                        "label" => "Configuraciones",
                        "url" => "#",
                        "icon" => "sitemap",
                        "items" => [
                            [
                                'label' => Yii::t('app', 'Proyectos'),
                                'icon' => 'folder-open-o',
                                'url' => Yii::$app->urlManager->createAbsoluteUrl(['project/']),
                                'visible' => $visibleProyectos
                            ],

                            [
                                'label' => Yii::t('app', 'Organizaciones'),
                                'icon' => 'home',
                                'url' => '#',
                                'visible' => $visibleOrganizacion || $visibleTipoOrg,
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Listado'),
                                        'icon' => 'list',
                                        'url' => Yii::$app->urlManager->createAbsoluteUrl(['organization/']),
                                        'visible' => $visibleOrganizacion
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Tipos'),
                                        'icon' => 'list',
                                        'url' => Yii::$app->urlManager->createAbsoluteUrl(['organization-type/']),
                                        'visible' => $visibleTipoOrg
                                    ],
                                ],
                            ],
                            [
                                'label' => Yii::t('app', 'Catálogos'),
                                'icon' => 'list',
                                'url' => Yii::$app->urlManager->createAbsoluteUrl(['data-list/']),
                                'visible' => $visibleCatalogo
                            ],
                            [
                                'label' => Yii::t('app', 'Segmentación'),
                                'icon' => 'filter',
                                'url' => Yii::$app->urlManager->createAbsoluteUrl(['filter/']),
                                'visible' => $visibleSegementacion
                            ],

                        ],
                    ],

                    [
                        'visible' => $user->getIsSuperUser(),
                        "label" => Yii::t('app', "Seguridad"),
                        "url" => "#",
                        "icon" => "lock",
                        "items" => [
                            [
                                "label" => Yii::t('app', "Usuarios"),
                                "url" => Yii::$app->urlManager->createAbsoluteUrl(['seguridad/usuarios/']),
                                'visible' => $visibleSeUsuarios,
                            ],
                            [
                                "label" => Yii::t('app', "Roles"),
                                "url" => Yii::$app->urlManager->createAbsoluteUrl(['seguridad/roles/']),
                                'visible' => $visibleSeRoles,
                            ],
                            [
                                "label" => Yii::t('app', "Rutas"),
                                "url" => Yii::$app->urlManager->createAbsoluteUrl(['seguridad/rutas/']),
                                'visible' => $visibleSeRutas,
                            ],
                            [
                                "label" => Yii::t('app', "Bitácora"),
                                "url" => Yii::$app->urlManager->createAbsoluteUrl(['audit/']),
                                'visible' => $visibleSeBitacora,
                            ],
                        ]
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
