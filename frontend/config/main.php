<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),

    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
        ],
        'view' => [
        //    'theme' => 'vova07\themes\site_default\Theme'
            //'theme' => 'vova07\themes_bs4\sb_admin\Theme'
        'theme' => 'vova07\themes_bs4\adminlte2\Theme',
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */

    ],
    //"aliases" => [
    //"@vova07/themes" => "@vendor_local/vova07/yii2-start-themes",
    //],
    'bootstrap' => [
        'log',
        //'vova07\themes\Bootstrap'
    ],
    'extensions' => array_merge(
        (require __DIR__. '/../../vendor/yiisoft/extensions.php'),
        [
            'vova07/themes' => [
                'name' => 'Application Information Dumper',
                'version' => '1.0.0',
                'bootstrap' => 'vova07\themes\Bootstrap',
                'alias' => ['@vova07/themes' => '@vendor_local/vova07/yii2-start-themes']
            ],
            'vova07/themes_bs4' => [
                'name' => 'Application Information Dumper1',
                'version' => '1.0.0',
                'bootstrap' => 'vova07\themes_bs4\Bootstrap',
                'alias' => ['@vova07/themes_bs4' => '@vendor_local/vova07/yii2-start-themes-bs4']
            ],




        ]

    ),
    'params' => $params,
];
