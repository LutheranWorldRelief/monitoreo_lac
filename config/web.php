<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Monitoreo',
    'charset' => 'utf-8',
    'language' => 'en',
    'sourceLanguage' => 'es-ES',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@nestic' => '@app/nestic',
        '@ImportJson' => '@app/import',
    ],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            // the layout that should be applied for views within this module
            'layout' => '@app/views/layouts/audit',
            // Name of the component to use for database access
            'ignoreActions' => ['audit/*', 'debug/*', 'gii/*', 'import/*'],
            // Role or list of roles with access to the viewer, null for everyone (if the user matches)
            'accessRoles' => ['Administrador', 'Administrador(a)'],
            // Maximum age (in days) of the audit entries before they are truncated
            'maxAge' => 10, // 30 days
            // Compress extra data generated or just keep in text? For people who don't like binary data in the DB
            'compressData' => false,
            // The callback to use to convert a user id into an identifier (username, email, ...). Can also be html.
            'userIdentifierCallback' => ['app\models\AuthUser', 'userIdentifierCallback'],
        ],
        'seguridad' => [
            'class' => 'app\modules\seguridad\Module',
            'controllerMap' => [
                'settings' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                ],
            ],
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu', // defaults to null, using the application's layout without the menu
            // other avaliable values are 'right-menu' and 'top-menu' left-menu
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => '\app\models\AuthUser',
                    'idField' => 'id'
                ]
            ],
        ],
    ],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
        ],
        'response' => [
            'formatters' => [
                'pdf' => [
                    'class' => 'app\nestic\pdf\PdfResponseFormatter',
                ],
                'pdf-h' => [
                    'class' => 'app\nestic\pdf\PdfLandscapeResponseFormatter',
                ],
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '3Lmk$al1.11651ab4#!¿',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\AuthUser',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => '\bedezign\yii2\audit\components\web\ErrorHandler',
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        'view' => [
            'class' => 'app\components\View',
            'title' => 'LWR Monitoring',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'forceTranslation' => true,
                    'useMoFile' => false,
                    'sourceLanguage' => 'es-ES',
                ],
                '*' => [
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'useMoFile' => false,
                    'sourceLanguage' => 'es-ES',
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
//            '*',
            'site/login',
            'site/logout',
//            'gii/*',
//            'some-controller/some-action',
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],
    'params' => $params,
    'timeZone' => 'America/Managua',
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '216.59.110.19'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => ['class' => 'coksnuss\gii\modelgen\generators\model\Generator'],
        ],
    ];
}

return $config;
