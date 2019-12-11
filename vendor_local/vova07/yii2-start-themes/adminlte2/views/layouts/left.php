<?php
use vova07\site\Module;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?=Yii::$app->user->identity->person->photo_preview_url ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->person->fio?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->


        <form action="#" method="post" class="sidebar-form">
            <div class="input-group">
                <?php echo \kartik\select2\Select2::widget(['class'=>'form-control','name'=>'id','data' => \vova07\users\models\Prisoner::getListForCombo(),'options'=>['prompt'=>'Quick Search'],
                    'pluginEvents' => ['change'=>'function(){location="' .\yii\helpers\Url::toRoute(['/users/prisoner/view']) . '" + "&id=" + $(this).val();}']
                    ])?>



            </div>
        </form>

        <!-- /.search form -->
        <?php

        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [

                    [
                        'label' => Module::t('menu','GENERAL_INFORMATION_HEADER_MENU'), 'options' => ['class' => 'header'],
                        'visible' => false,


                    ],
                    ['label' => Module::t('menu','DASH_BOARD_MENU'),'icon' => 'tachometer','url'=>['/site/dash-board/index']],
                    ['label' => Module::t('menu','PRISONERS_LIST'),'icon' => 'users','url'=>['/users/prisoner'],
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST),
                    ],
                    ['label' => Module::t('menu','PRISONERS_SECURITY_LIST'),'icon' => 'hourglass','url'=>['/prisons/prisoner-security/index'],
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_LIST),
                        ],
                 /*   ['label' => Module::t('menu','STATISTICS_MENU'),'icon' => 'pie-chart','url'=>['#'],
                        'items' => [
                            ['label' => Module::t('menu','PROGRAMS_MENU'),'icon' => 'circle','url'=>['']],
                            ['label' => Module::t('menu','JOBS_MENU'),'icon' => 'circle','url'=>['']],
                            ['label' => Module::t('menu','WORKSHOPS_MENU'),'icon' => 'circle','url'=>['']],
                        ]
                    ],*/
                    ['label' =>  Module::t('menu', 'ACTS_AND_DOCUMENTS_MENU'),'icon' => 'files','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_DOCUMENTS_LIST),
                        'items' => [
                            ['label' => Module::t('menu','DOCUMENTS_LIST'),'icon' => 'circle','url'=>['/documents/default/index']],
                        //    ['label' => Module::t('menu','BLANKS_LIST'),'icon' => 'circle','url'=>['/documents/blanks/index']],
                          //  ['label' => Module::t('menu','PRISONER_BLANKS_LIST'),'icon' => 'circle','url'=>['/documents/blank-prisoners/index']],
                        ]
                    ],
                    [
                            'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_EVENT_PLANING_LIST),
                            'label' =>  Module::t('menu', 'EVENTS_MENU'),'icon' => 'calendar','url' => '#' ,
                        'items' => [
                            ['label' => Module::t('menu','EVENTS_LIST'),'icon' => 'circle','url'=>['/events/default/index']],

                        ]
                    ],
                    ['label' =>  Module::t('menu', 'TASKS_MENU'),'icon' => 'calendar-check','url' => '#' ,
                        'items' => [
                            ['label' => Module::t('menu','COMMITTIES_LIST'),'icon' => 'circle','url'=>['/tasks/committee/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_COMMITTIEE_LIST),
                                ],

                        ]
                    ],
                    ['label' =>  Module::t('menu', 'HUMANITARIAN_MENU'),'icon' => 'cube','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_HUMANITARIAN_LIST),
                        'items' => [

                            ['label' => Module::t('menu','HUMANITARIAN_LIST'),'icon' => 'circle','url'=>['/humanitarians/issues/index']],
                            ['label' => Module::t('menu','HUMANITARIAN_ITEMS_LIST'),'icon' => 'circle','url'=>['/humanitarians/items/index']],
                        ]
                    ],
                    ['label' =>  Module::t('menu', 'JOBS_MENU'),'icon' => 'cube','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_JOBS_ACCESS),
                        'items' => [
                            ['label' => Module::t('menu','AVAILABLE_JOBS_LIST'),'icon' => 'circle','url'=>['/jobs/job-list/index']],
                            ['label' => Module::t('menu','JOBS_LIST'),'icon' => 'circle','url'=>['/jobs/default/index']],
                            ['label' => Module::t('menu','NOT_PAID_JOBS_LIST'),'icon' => 'circle','url'=>['/jobs/not-paid-jobs/index']],
                            ['label' => Module::t('menu','NOT_PAID_TYPES_LIST'),'icon' => 'circle','url'=>['/jobs/not-paid-types/index']],
                            ['label' => Module::t('menu','PAID_TYPES_LIST'),'icon' => 'circle','url'=>['/jobs/paid-types/index']],


                        ]
                    ],
                    ['label' =>  Module::t('menu', 'FINANCE_MENU'),'icon' => 'credit-card','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_FINANCES_ACCESS),
                        'items' => [
                            ['label' => Module::t('menu','BALANCE_LIST'),'icon' => 'circle','url'=>['/finances/default/index']],
                        ]
                    ],
                    ['label' =>  Module::t('menu', 'ELECTRICITY_MENU'),'icon' => 'tv','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_FINANCES_ACCESS),
                        'items' => [
                            ['label' => Module::t('menu','DEVICES_ACCOUNTING_LIST'),'icon' => 'circle','url'=>['/electricity/default/index']],
                            ['label' => Module::t('menu','DEVICES_LIST'),'icon' => 'circle','url'=>['/electricity/devices/index']],
                            [
                                    'label' => Module::t('menu','BALANCE_IMPORT'),'icon' => 'circle','url'=>['/electricity/balance-import/index'],
                                    'template' => '<a href="{url}">{icon} {label}' .((\vova07\electricity\models\DeviceAccounting::find()->readyForProcessing()->count()>0)?\yii\helpers\Html::tag('span',
                                            \yii\helpers\Html::tag('span',\vova07\electricity\models\DeviceAccounting::find()->readyForProcessing()->count(),['class' => "label label-danger pull-right"])
                                            ,['class' =>  "pull-right-container"]):""). '</a>'


                            ],

                        ]
                    ],
                    ['label' =>  Module::t('menu', 'PROGRAMS_MENU'),'icon' => 'calendar-check','url' => '#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_VIEW),
                        'items' => [
                            ['label' => Module::t('menu','PROGRAMS_DICTS_LIST'),'icon' => 'circle','url'=>['/plans/program-dicts'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PROGRAM_PLANING_LIST)],
                            ['label' => Module::t('menu','PROGRAMS_LIST'),'icon' => 'circle','url'=>['/plans/programs'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_VIEW)],
                            ['label' => Module::t('menu','PROGRAM_PLANS_LIST'),'icon' => 'circle','url'=>['/plans/program-plans'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_VIEW)],



                        ]
                    ],
                    ['label' => Module::t('menu','ADMINISTRATION_HEADER_MENU'), 'options' => ['class' => 'header'],
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)
                    ],
                    ['label' =>  Module::t('menu', 'AUTHORIZE_MENU'),'icon' => 'barcode','url' => '#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC),
                        'items' => [
                            ['label' => Module::t('menu','RBAC_PERMISSION_MENU'),'icon' => 'circle','url'=>['/rbac/permissions'],
                            'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)
                            ],
                            [
                                    'label' => Module::t('menu','RBAC_ROLES_MENU'),'icon' => 'circle','url'=>['/rbac/roles'],
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)
                            ],
                            ['label' => Module::t('menu','RBAC_RULES_MENU'),'icon' => 'circle','url'=>['/rbac/rules'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)]
                        ]
                    ],
                    ['label' =>  Module::t('menu', 'USERS_MENU'),'icon' => 'users','url' => '#',
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC),
                        'items' => [
                            ['label' => Module::t('menu','USERS_LIST'),'icon' => 'circle','url'=>['/users/default'],
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','OFFICERS_LIST'),'icon' => 'circle','url'=>['/users/officers'],
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','PEOPLE_LIST'),'icon' => 'circle','url'=>['/users/people'],
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                        ]
                    ],
                    ['label' =>  Module::t('menu', 'PRISONS_MENU'),'icon' => 'bank','url' => '#',
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC),
                        'items' => [
                            ['label' => Module::t('menu','COMPANIES_LIST'),'icon' => 'circle','url'=>['/prisons/companies'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','DEPARTMENTS_LIST'),'icon' => 'circle','url'=>['/prisons/departments'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','PRISONS_LIST'),'icon' => 'circle','url'=>['/prisons/default'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],

                        ]
                    ],
                     ['label' =>  Module::t('menu', 'SETTINGS'),'icon' => 'language','url' => ['/site/settings/index'],
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],

                    ['label' =>  Module::t('menu', 'PRISONERS_JOURNAL'),'icon' => 'truck','url' => ['/users/prisoners-journal/index'],
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)
                        ],

                        ['label' =>  Module::t('menu', 'TRANSLATION'),'icon' => 'language','url' => ['/translations/default/index'],
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)]

                ],
            ]

        ) ?>

    </section>

</aside>
