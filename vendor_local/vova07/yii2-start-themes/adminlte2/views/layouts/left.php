<?php
use vova07\site\Module;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <?php if (Yii::$app->user->identity->person):?>
            <div class="pull-left image">
                <img style = "max-width:70px !important" src="<?=Yii::$app->user->identity->person->photo_preview_url ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->person->fio?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
            <?php endif;?>
        </div>

        <?php // if (Yii::$app->params['quickSwitchUser']):
            if (Yii::$app->session->get(\vova07\users\Module::QUICK_SWITCH_USER_ENABLED_SESSION_PARAM_NAME, false)):
        ?>

        <!-- quit switch form -->

        <form action="#" method="post" class="sidebar-form">
            <div class="input-group">
                <?php echo \kartik\select2\Select2::widget(['class'=>'form-control','value'=> Yii::$app->user->id, 'name'=>'id','data' => \vova07\users\models\User::getListForCombo(),'options'=>['prompt'=>'Quick switch user'],
                    'pluginEvents' => ['change'=>'function(){location="' .\yii\helpers\Url::toRoute(['/site/default/switch-user']) . '" + "&id=" + $(this).val();}']
                ])?>



            </div>
        </form>
        <?php endif;?>

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
                    ['label' => Module::t('menu','DASH_BOARD_MENU'),'icon' => 'tachometer-alt','url'=>['/site/dash-board/index']],
                    ['label' => Module::t('menu','PRISONERS_LIST'),'icon' => 'users','url'=>'#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST),
                      'items' => [
                          ['label' => Module::t('menu','PRISONERS_LIST'),'icon' => 'circle','url'=>['/users/prisoner'],
                              'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST),

                          ],
                          ['label' => Module::t('menu','CELLS_PRISONERS_LIST'),'icon' => 'circle','url'=>['/users/prisoner/cells'],
                              'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST),

                          ],



                      ]
                    ],
                    ['label' => Module::t('menu','PRISONERS_SECURITY_LIST'),'icon' => 'hourglass','url'=>['/prisons/prisoner-security/index'],
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_LIST),
                        ],
                    ['label' =>  Module::t('menu', 'TASKS_MENU'),'icon' => 'calendar-check','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_COMMITTIEE_LIST),
                        'items' => [
                            ['label' => Module::t('menu','COMMITTIES_LIST'),'icon' => 'circle','url'=>['/tasks/committee/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_COMMITTIEE_LIST),
                            ],

                        ]
                    ],
                    [
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_EVENT_PLANING_LIST),
                        'label' =>  Module::t('menu', 'EVENTS_MENU'),'icon' => 'calendar','url' => '#' ,
                        'items' => [
                            ['label' => Module::t('menu','EVENTS_LIST'),'icon' => 'circle','url'=>['/events/default/index']],

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

                            ['label' => Module::t('menu','PROGRAM_PRISONER_LIST'),'icon' => 'circle','url'=>['/plans/program-prisoners'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','SUMMARIZED_LIST'),'icon' => 'circle','url'=>['/plans/summarized/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PROGRAMS_SUMMARIZED_LIST)],

                            ['label' => Module::t('menu','SUMMARIZED_PROGRAMS_LIST'),'icon' => 'circle','url'=>['/plans/summarized/programs'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PROGRAMS_SUMMARIZED_LIST)],


                        ]
                    ],
                    ['label' =>  Module::t('menu', 'CONCEPTS_MENU'),'icon' => 'calendar-check','url' => '#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_CONCEPTS_LIST),
                        'items' => [
                            [
                                'label' => Module::t('menu','CONCEPTS_LIST'),'icon' => 'circle','url'=>['/concepts/default'],
                                'visible' => true,
                            ],
                            [
                                'label' => Module::t('menu','PARTICIPANTS_LIST'),'icon' => 'circle','url'=>['/concepts/participants'],
                                'visible' => true,
                            ]
                        ]
                    ],
                    ['label' =>  Module::t('menu', 'ACTS_AND_DOCUMENTS_MENU'),'icon' => 'file-alt','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_DOCUMENTS_LIST),
                        'items' => [
                            ['label' => Module::t('menu','DOCUMENTS_LIST'),'icon' => 'circle','url'=>['/documents/default/index']],
                            ['label' => Module::t('menu','DOCUMENTS_REPORT_LIST'),'icon' => 'circle','url'=>['/documents/report/index']],
                            ['label' => Module::t('menu','DOCUMENTS_REPORT_INDEX2'),'icon' => 'circle','url'=>['/documents/report/index2']],

                            //    ['label' => Module::t('menu','BLANKS_LIST'),'icon' => 'circle','url'=>['/documents/blanks/index']],
                            //  ['label' => Module::t('menu','PRISONER_BLANKS_LIST'),'icon' => 'circle','url'=>['/documents/blank-prisoners/index']],
                        ]
                    ],
                    ['label' =>  Module::t('menu', 'PSYCHO_MENU'),'icon' => 'calendar-check','url' => '#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PSYCHO_LIST),
                        'items' => [
                            [
                                'label' => Module::t('menu','PSYCHO_CHARACTERISTICS_LIST'),'icon' => 'circle','url'=>['/psycho/default'],
                                'visible' => true,
                            ],
                            [
                                'label' => Module::t('menu','PSYCHO_TESTS_LIST'),'icon' => 'circle','url'=>['/psycho/tests'],
                                'visible' => true,
                            ]
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
                            ['label' => Module::t('menu','PENALTY_LIST'),'icon' => 'circle','url'=>['/prisons/penalties/index']],


                        ]
                    ],

                    /*   ['label' => Module::t('menu','STATISTICS_MENU'),'icon' => 'pie-chart','url'=>['#'],
                           'items' => [
                               ['label' => Module::t('menu','PROGRAMS_MENU'),'icon' => 'circle','url'=>['']],
                               ['label' => Module::t('menu','JOBS_MENU'),'icon' => 'circle','url'=>['']],
                               ['label' => Module::t('menu','WORKSHOPS_MENU'),'icon' => 'circle','url'=>['']],
                           ]
                       ],*/



                    ['label' =>  Module::t('menu', 'FINANCE_MENU'),'icon' => 'credit-card','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_FINANCES_ACCESS),
                        'items' => [
                            ['label' => Module::t('menu','BALANCE_LIST'),'icon' => 'circle','url'=>['/finances/default/index']],
                            [
                                'label' => Module::t('menu','BALANCE_IMPORT'),'icon' => 'circle','url'=>['/electricity/balance-import/index'],
                                'template' => '<a href="{url}">{icon} {label}' .((\vova07\electricity\models\DeviceAccounting::find()->readyForProcessing()->count()>0)?\yii\helpers\Html::tag('span',
                                        \yii\helpers\Html::tag('span',\vova07\electricity\models\DeviceAccounting::find()->readyForProcessing()->count(),['class' => "label label-danger pull-right"])
                                        ,['class' =>  "pull-right-container"]):""). '</a>',

                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ELECTRICITY_BALANCE_IMPORT),

                            ],
                            ['label' => Module::t('menu','BALANCE_RANG_LIST'),'icon' => 'circle','url'=>['/finances/default/depts-by-rang']],
                        ]
                    ],
                    ['label' =>  Module::t('menu', 'ELECTRICITY_MENU'),'icon' => 'tv','url' => '#' ,
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ELECTRICITY_ACCESS),
                        'items' => [
                            [
                                    'label' => Module::t('menu','DEVICES_LIST'),'icon' => 'circle','url'=>['/electricity/devices/index'],
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ELECTRICITY_ACCESS),

                            ],
                            ['label' => Module::t('menu','DEVICES_ACCOUNTING_LIST'),'icon' => 'circle','url'=>['/electricity/default/index'],
                               'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ELECTRICITY_ACCESS),

                            ],

                            ['label' => Module::t('menu','DEVICES_SUMMARIZED_LIST'),'icon' => 'circle','url'=>['/electricity/summarized/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ELECTRICITY_SUMMARIZED_LIST),

                            ],


                        ]
                    ],


                    ['label' =>  Module::t('menu', 'REPORTS_MENU'),'icon' => 'calendar-check','url' => '#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_VIEW),
                        'items' => [

                            ['label' => Module::t('menu','REPORT_PRISONERS'),'icon' => 'circle','url'=>['/users/report/prisoners'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST),

                            ],
                            ['label' => Module::t('menu','REPORT_PLANS'),'icon' => 'circle','url'=>['/plans/report'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST),

                            ],
                            ['label' => Module::t('menu','REPORT_SUMMARIZED'),'icon' => 'circle','url'=>['/prisons/reports/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST)],
                            ['label' => Module::t('menu','JOBS_GENERAL_LIST'),'icon' => 'circle','url'=>['/jobs/general-list/index']],



                            ['label' => Module::t('menu','REPORT_PRISONERS_LOCATION_JOURNAL'),'icon' => 'circle','url'=>['/users/report/location-journal'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST)],
                            ['label' => Module::t('menu','REPORT_PRISONERS_PROGRAM'),'icon' => 'circle','url'=>['/reports/prisoner-program/index']],

                        ]
                    ],





                    ['label' =>  Module::t('menu', 'SALARY_MENU'),'icon' => 'calendar-check','url' => '#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_SALARY_ACCEPT),
                        'items' => [
                            [
                                'label' => Module::t('menu','SALARY_LIST'),'icon' => 'circle','url'=>['/salary/default'],
                                'items' => [
                                        [
                                            'label' => Module::t('menu','SALARY'),'icon' => 'circle','url'=>['/salary/default'],
                                        ],
                                    [
                                        'label' => Module::t('menu','SALARY_CHARGE'),'icon' => 'circle','url'=>['/salary/default/charge'],
                                    ],
                                    [
                                        'label' => Module::t('menu','SALARY_WITHHOLD'),'icon' => 'circle','url'=>['/salary/default/withhold'],
                                    ],

                                ],

                                'visible' => true,
                            ],

                            [
                                'label' => Module::t('menu','SALARY_CLASSES_LIST'),'icon' => 'circle','url'=>['/salary/salary-classes'],
                                'visible' => true,
                            ],

                        ]
                    ],

                    [
                        'label' =>  Yii::$app->base->company->title,'icon' => 'calendar-check','url' => '#',
                        'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_SALARY_ACCEPT),
                        'items' => [
                            ['label' => Module::t('menu','POST_ISOS_LIST'),'icon' => 'circle','url'=>['/prisons/post-isos/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],

                            ['label' => Module::t('menu','POST_DICT_LIST'),'icon' => 'circle','url'=>['/prisons/post-dicts/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','OFFICER_POSTS_LIST'),'icon' => 'circle','url'=>['/prisons/officer-posts/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],

                            ['label' => Module::t('menu','OFFICER_POSTS_EXTENDED_LIST'),'icon' => 'circle','url'=>['/prisons/officer-posts/officer-posts'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],


                            ['label' => Module::t('menu','RANKS_LIST'),'icon' => 'circle','url'=>['/prisons/ranks/index'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],


                            ['label' => Module::t('menu','DIVISIONS_AND_POSS'),'icon' => 'circle','url'=>['/prisons/divisions/index', 'DivisionSearch' => ['company_id' => Yii::$app->base->company->primaryKey]],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)
                            ]



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
                    ['label' =>  Module::t('menu', 'PRISONS_MENU'),'icon' => 'cube','url' => '#',
                                    'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC),
                        'items' => [

                            ['label' => Module::t('menu','COMPANIES_LIST'),'icon' => 'circle','url'=>['/prisons/companies'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','DEPARTMENTS_LIST'),'icon' => 'circle','url'=>['/prisons/departments'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],
                            ['label' => Module::t('menu','PRISONS_LIST'),'icon' => 'circle','url'=>['/prisons/default'],
                                'visible' => \vova07\rbac\helpers\Rbac::checkAccess(\vova07\rbac\Module::PERMISSION_ADMINISTRATE_RBAC)],

                            ['label' => Module::t('menu','PLAN_GROUPS'),'icon' => 'circle','url'=>['/plans/groups'],
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
