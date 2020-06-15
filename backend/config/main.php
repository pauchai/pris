<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => "Prisons Admin",
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'site/dash-board/index',
    'on ' . \yii\base\Application::EVENT_BEFORE_REQUEST => function() {
       // $languages = [
       //     'ru' => 'ru-RU',
       //     'ro' => 'ro-RO',
       //     'en' => 'en-EN',
       // ];
        if (Yii::$app->params['quickSwitchUser'] && Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_QUICK_SWITCH_USER)){
            Yii::$app->session->set(\vova07\users\Module::QUICK_SWITCH_USER_ENABLED_SESSION_PARAM_NAME,true);
        }
        if (Yii::$app->params['maintenanceMode']){
           Yii::$app->catchAll = ['site/default/maintenance'];
        }
        $matches  = [];
        preg_match("#index-(\w+\-\w+)\.php#", $_SERVER['PHP_SELF'], $matches);
        Yii::$app->base->company = \vova07\prisons\models\Company::findOne(\vova07\prisons\models\Company::ID_PRISON_PU1);
        if (isset($matches[1])){
            Yii::$app->language = $matches[1];
        } else {
            Yii::$app->language = 'ro-RO';
        };
    },
   // 'controllerNamespace' => 'backend\controllers',
    'modules' => [
        'users' => [
            'appContext' => 'backend',
        ],
        'site' => [
            'appContext' => 'backend',
        ],
        'prisons' => [
            'appContext' => 'backend',
        ],
        'plans' => [
            'appContext' => 'backend',
        ],
        'events' => [
            'appContext' => 'backend',
        ],
        'videos' => [
            'appContext' => 'backend'
        ],
        'rbac' => [
            'appContext' => 'backend'
        ],
        'tasks' => [
            'appContext' => 'backend'
        ],
        'documents' => [
            'appContext' => 'backend'
        ],
        'humanitarians' => [
            'appContext' => 'backend'
        ],
        'jobs' => [
            'appContext' => 'backend'
        ],
        'finances' => [
            'appContext' => 'backend'
        ],
        'electricity' => [
            'appContext' => 'backend'
        ],
        'psycho' => [
            'appContext' => 'backend'
        ],
        'comments' => [
            'appContext' => 'backend'
        ],
        'concepts' => [
            'appContext' => 'backend'
        ],
        'salary' => [
            'appContext' => 'backend'
        ],
        'biblio' => [
            'appContext' => 'backend'
        ],



        'translations' => [
            'class' => uran1980\yii\modules\i18n\Module::className(),
            'controllerMap' => [
                'default' => uran1980\yii\modules\i18n\controllers\DefaultController::className(),
            ],
            // example for set access control to module (if required):
            'as access' => [
                'class' => yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'controllers'   => ['translations/default'],
                        'actions'       => ['index', 'save', 'update', 'rescan', 'clear-cache', 'delete', 'restore', 'clear-deleted'],
                        'allow'         => true,
                        'roles'         => ['superadmin'],
                    ]
                ],
            ],
        ],
        'gridview' => [
            'class' => \kartik\grid\Module::class
        ]


    ],

    'components' => [
        'view' => [
            'theme' => 'vova07\themes\adminlte2\Theme'
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],

        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'errorAction' => 'site/default/error'
        ],
        'cache' => [

               'class' => \yii\caching\FileCache::class,
        ],
        'base' => [
            'class' => \vova07\base\components\Base::class,

        ]
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */


    ],
    'bootstrap' => [
        'log',
    ],
    'extensions' => array_merge(
        (require __DIR__. '/../../vendor/yiisoft/extensions.php'),
        [

            'vova07/themes_bs4' => [
                'name' => 'Application Information Dumper1',
                'version' => '1.0.0',
               // 'bootstrap' => 'vova07\themes_bs4\Bootstrap',
               // 'alias' => ['@vova07/themes_bs4' => '@vendor_local/vova07/yii2-start-themes-bs4']
                 'bootstrap' => 'vova07\themes\Bootstrap',
                 'alias' => ['@vova07/themes' => '@vendor_local/vova07/yii2-start-themes']
            ],




        ]

    ),
    'params' => $params,
];
