<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
        'authManager' => [
            'class' => yii\rbac\PhpManager::class,
            'defaultRoles' => [
                'user'
            ],
            'itemFile' => __DIR__ . '/../tests/_data/rbac/items.php',
            'assignmentFile' => __DIR__ . '/../tests/_data/rbac/assignments.php',
            'ruleFile' => __DIR__ . '/../tests/_data/rbac/rules.php',
        ],
    ],
];
