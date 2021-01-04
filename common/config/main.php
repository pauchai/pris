<?php
return [
   // 'language' => "ro-RO",
    //'sourceLanguage' => 'ro-RO',
    'sourceLanguage' => 'en-EN',
    //'sourceLanguage' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'container' => [
      'definitions' => [
          \vova07\base\components\DateConvertJuiBehavior::class => [
              'dateFormat' => 'd-m-Y','dateConvertFormat' => 'Y-m-d'
          ],
          \yii\validators\DateValidator::class => ['format' => 'dd-MM-yyyy'],
          \vova07\base\components\DateJuiBehavior::class => ['dateFormat' => 'd-m-Y'],
          \kartik\widgets\DatePicker::class => [
              'defaultOptions' => [
                  'class' => 'form-control',
                  'autocomplete' => 'off',
                  'placeholder' => Yii::t('default','SELECT_DATE'),
              ],
              'pluginOptions' => [
                  'format' => 'dd-mm-yyyy'
              ]
          ],
          \yii\jui\DatePicker::class => [
              'options' => [
                  'class' => 'form-control',
                  'autocomplete' => 'off',
                  'placeholder' => Yii::t('default','SELECT_DATE'),
              ],
              'dateFormat' => 'dd-MM-yyyy',
          ],
          \kartik\date\DatePicker::class => [
              'defaultOptions' => [
                  'class' => 'form-control',
                  'autocomplete' => 'off',
                  'placeholder' => Yii::t('default','SELECT_DATE'),
              ],
              'pluginOptions' => [
                  'format' => 'dd-mm-yyyy'
              ]
          ],
          \kartik\grid\GridView::class => [

          ],
          yii\widgets\LinkPager::class => [
              'firstPageLabel' => 'First',
              'lastPageLabel'  => 'Last'
          ]
      ]
    ],

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' =>[
            'class' => 'yii\web\User',
            'identityClass' => 'vova07\users\models\User',
            'loginUrl' => ['/site/default/login']
        ],
        'i18n' => [
            'class'      => uran1980\yii\modules\i18n\components\I18N::class,
            'languages'  => ['en', 'ro', 'ru'],
            // Or, if you manage languages in database
            //'languages'  => function() {
            //    /* /!\ Make sure the result is a mere list of language codes, and the
            //     * one used in views is the first one */
            //    return \namespace\of\your\LanguageClass::find()->where(['active' => true'])->orderBy('default' => SORT_DESC])->select('code')->column();
            //},
            'format'     => 'db',
            'sourcePath' => [
                __DIR__ . '/../../frontend',
                __DIR__ . '/../../backend',
                __DIR__ . '/../../common',
            ],
            'messagePath' => __DIR__  . '/../../messages',
            // Whether database messages are to be used instead of view ones.
            // Enables editing messages in locale specified by
            // Yii::$app->sourceLanguage
            // Can be set per translation category too
            'forceTranslation' => true,
            'on missingTranslation' => null,

        ],



        'authManager' => [
            'class' => yii\rbac\PhpManager::class,
            'defaultRoles' => [
                'user'
            ],
            'itemFile' => '@vova07/rbac/data/items.php',
            'assignmentFile' => '@vova07/rbac/data/assignments.php',
            'ruleFile' => '@vova07/rbac/data/rules.php',
        ],

    ],


    'modules' => [

        'users' => [
            'class' => 'vova07\users\Module',
            'appContext' => 'backend',
        ],
        'site' => [
            'class' => 'vova07\site\Module',

        ],
        'prisons' => [
            'class' => 'vova07\prisons\Module',

        ],
        'plans' => [
            'class' => \vova07\plans\Module::class,

        ],
        'events' => [
            'class' => \vova07\events\Module::class,

        ],
        'videos' => [
            'class' => \vova07\videos\Module::class
        ],

        'rbac' => [
            'class' => 'vova07\rbac\Module',

        ],
        'tasks' => [
            'class' => \vova07\tasks\Module::class,

        ],
        'documents' => [
            'class' => \vova07\documents\Module::class,

        ],
        'humanitarians' => [
            'class' => \vova07\humanitarians\Module::class,

        ],
        'jobs' => [
            'class' => \vova07\jobs\Module::class,

        ],
        'finances' => [
            'class' => \vova07\finances\Module::class,

        ],
        'electricity' => [
            'class' => \vova07\electricity\Module::class,

        ],
        'psycho' => [
            'class' => \vova07\psycho\Module::class,

        ],
        'comments' => [
            'class' => \vova07\comments\Module::class,

        ],
        'concepts' => [
            'class' => \vova07\concepts\Module::class,

        ],
        'salary' => [
            'class' => \vova07\salary\Module::class,

        ],
        'biblio' => [
            'class' => \vova07\biblio\Module::class,

        ],
        'reports' => [
            'class' => \vova07\reports\Module::class,

        ],
    ],

    'extensions' => array_merge(
        (require __DIR__. '/../../vendor/yiisoft/extensions.php'),
        (require  'extensions.php')
        )


];
