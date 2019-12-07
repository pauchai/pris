<?php

use vova07\themes\admin\widgets\Menu;
echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            [
                'label' => Yii::t('vova07/themes/admin', 'Dashboard'),
                'url' => Yii::$app->homeUrl,
                'icon' => 'fa-dashboard',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl
            ],

            [
                'label' => Yii::t('vova07/themes/admin', 'Prisons'),
                'url' => ['/prisons/default/index'],
                'icon' => 'fa-book',
                'visible' => Yii::$app->user->can('administratePrisons') || Yii::$app->user->can('BViewPrisons'),
                'items' => [
                    [
                        'label' => Yii::t('vova07/themes/admin', 'list'),
                        'url' => ['/prisons/default/index'],
                        'visible' => true,
                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'People'),
                        'url' => ['/prisons/person/index'],
                        'visible' => true,
                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Documents'),
                        'url' => ['/prisons/documents/index'],
                        'visible' => true,
                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Sheet'),
                        'url' => ['/prisons/sheet/index'],
                        'visible' => true,
                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Programs'),
                        'url' => ['/prisons/program/index'],
                        'visible' => true,
                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'FamilyStatus'),
                        'url' => ['/prisons/family-status/index'],
                        'visible' => true,
                    ],


                ],

            ],
            [
                'label' => Yii::t('vova07/themes/admin', 'Activities'),
                'url' => ['/prisons/activities/index'],
                'icon' => 'fa-group',
                'visible' => true,
            ],
            [
                'label' => Yii::t('vova07/themes/admin', 'Employers'),
                'url' => ['/users/default/index'],
                'icon' => 'fa-group',
                'visible' => Yii::$app->user->can('administrateUsers') || Yii::$app->user->can('BViewUsers'),
            ],

            [
                'label' => Yii::t('vova07/themes/admin', 'Comments'),
                'url' => ['/comments/default/index'],
                'icon' => 'fa-comments',
                'visible' => Yii::$app->user->can('administrateComments') || Yii::$app->user->can('BViewCommentsModels') || Yii::$app->user->can('BViewComments'),
                'items' => [
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Comments'),
                        'url' => ['/comments/default/index'],
                        'visible' => Yii::$app->user->can('administrateComments') || Yii::$app->user->can('BViewComments'),
                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Models management'),
                        'url' => ['/comments/models/index'],
                        'visible' => Yii::$app->user->can('administrateComments') || Yii::$app->user->can('BViewCommentsModels'),
                    ]
                ]
            ],
            [
                'label' => Yii::t('vova07/themes/admin', 'Access control'),
                'url' => ['/rbac/permissions/index'],
                'icon' => 'fa-gavel',
                'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles') || Yii::$app->user->can('BViewPermissions') || Yii::$app->user->can('BViewRules'),
                'items' => [
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Permissions'),
                        'url' => ['/rbac/permissions/index'],
                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewPermissions')

                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Roles'),
                        'url' => ['/rbac/roles/index'],
                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles')
                    ],
                    [
                        'label' => Yii::t('vova07/themes/admin', 'Rules'),
                        'url' => ['/rbac/rules/index'],
                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRules')
                    ]
                ]
            ],
        ]
    ]
);
