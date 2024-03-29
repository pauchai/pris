<?php
return [
    'vova07/base' => [
        'name' => 'Application Information Dumper1',
        'version' => '1.0.0',
        'bootstrap' => 'vova07\base\Bootstrap',
        'alias' => ['@vova07/base' => '@vendor_local/vova07/yii2-start-base']
    ],

    'vova07/users' => [
        'name' => 'Application Information Dumper1',
        'version' => '1.0.0',
        'bootstrap' => 'vova07\users\Bootstrap',
        'alias' => ['@vova07/users' => '@vendor_local/vova07/yii2-start-users-module']
    ],
    'vova07/site' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => 'vova07\site\Bootstrap',
        'alias' => ['@vova07/site' => '@vendor_local/vova07/yii2-start-site-module']
    ],
    'vova07/countries' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => 'vova07\countries\Bootstrap',
        'alias' => ['@vova07/countries' => '@vendor_local/vova07/yii2-start-countries-module']
    ],
    'vova07/prisons' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => 'vova07\prisons\Bootstrap',
        'alias' => ['@vova07/prisons' => '@vendor_local/vova07/yii2-start-prisons-module']
    ],
    'vova07/plans' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\plans\Bootstrap::class,
        'alias' => ['@vova07/plans' => '@vendor_local/vova07/yii2-start-plans-module']
    ],
    'vova07/videos' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => 'vova07\videos\Bootstrap',
        'alias' => ['@vova07/videos' => '@vendor_local/vova07/yii2-start-videos-module']
    ],
    'vova07/rbac' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => 'vova07\rbac\Bootstrap',
        'alias' => ['@vova07/rbac' => '@vendor_local/vova07/yii2-start-rbac-module']
    ],
    'vova07/events' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\events\Bootstrap::class,
        'alias' => ['@vova07/events' => '@vendor_local/vova07/yii2-start-events-module']
    ],
    'vova07/tasks' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\tasks\Bootstrap::class,
        'alias' => ['@vova07/tasks' => '@vendor_local/vova07/yii2-start-tasks-module']
    ],
    'vova07/documents' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\documents\Bootstrap::class,
        'alias' => ['@vova07/documents' => '@vendor_local/vova07/yii2-start-documents-module']
    ],
    'vova07/humanitarians' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\humanitarians\Bootstrap::class,
        'alias' => ['@vova07/humanitarians' => '@vendor_local/vova07/yii2-start-humanitarians-module']
    ],
    'vova07/jobs' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\jobs\Bootstrap::class,
        'alias' => ['@vova07/jobs' => '@vendor_local/vova07/yii2-start-jobs-module']
    ],
    'vova07/finances' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\finances\Bootstrap::class,
        'alias' => ['@vova07/finances' => '@vendor_local/vova07/yii2-start-finances-module']
    ],

    'vova07/electricity' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\electricity\Bootstrap::class,
        'alias' => ['@vova07/electricity' => '@vendor_local/vova07/yii2-start-electricity-module']
    ],
    'vova07/psycho' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\psycho\Bootstrap::class,
        'alias' => ['@vova07/psycho' => '@vendor_local/vova07/yii2-start-psycho-module']
    ],
    'vova07/comments' => [
        'name' => 'Application Information Dumper',
        'version' => '1.0.0',
        'bootstrap' => \vova07\comments\Bootstrap::class,
        'alias' => ['@vova07/comments' => '@vendor_local/vova07/yii2-start-comments-module']
    ],
    'vova07/concepts' => [
        'name' => 'Concepts Module',
        'version' => '1.0.0',
        'bootstrap' => \vova07\concepts\Bootstrap::class,
        'alias' => ['@vova07/concepts' => '@vendor_local/vova07/yii2-start-concepts-module']
    ],
    'pauk/recurinput' => [
        'name' => 'Yii2 RecurInput widget',
        'version' => '1.0.0',
        'bootstrap' => \pauk\recurinput\Bootstrap::class,
        'alias' => ['@pauk/recurinput' => '@vendor_local/pauk/yii2-recurinput']
    ],
    'vova07/salary' => [
        'name' => 'Salary Module',
        'version' => '1.0.0',
        'bootstrap' => \vova07\salary\Bootstrap::class,
        'alias' => ['@vova07/salary' => '@vendor_local/vova07/yii2-start-salary-module']
    ],
    'vova07/biblio' => [
        'name' => 'Biblio Module',
        'version' => '1.0.0',
        'bootstrap' => \vova07\biblio\Bootstrap::class,
        'alias' => ['@vova07/biblio' => '@vendor_local/vova07/yii2-start-biblio-module']
    ],
    'vova07/reports' => [
        'name' => 'Reports Module',
        'version' => '1.0.0',
        'bootstrap' => \vova07\reports\Bootstrap::class,
        'alias' => ['@vova07/reports' => '@vendor_local/vova07/yii2-start-reports-module']
    ],
    'vova07/socio' => [
        'name' => 'Socio Module',
        'version' => '1.0.0',
        'bootstrap' => \vova07\socio\Bootstrap::class,
        'alias' => ['@vova07/socio' => '@vendor_local/vova07/yii2-start-socio-module']
    ],
];
