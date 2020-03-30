<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use kartik\grid\GridView;
use \vova07\plans\Module;
$this->title = \vova07\plans\Module::t("default","SUMMARIZED_VIEW");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?=$this->render('_search',['model' => $searchModel])?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'summary' => '',
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'class' => \yii\grid\DataColumn::class,
            'attribute' => 'at',
            'content' => function($model){
                if ( Yii::$app->formatter->format($model->at, ['date','php:N']) == 7)
                    $options = ['class' => 'label label-danger'];
                else
                    $options = ['class' => 'label label-success'];
                return \yii\bootstrap\Html::tag('span', Yii::$app->formatter->format($model->at, ['date','php:D']),$options ) .
                    Yii::$app->formatter->format($model->at, ['date','php:d-m-Y']) . ' ';
            },
            //'format' => ['date','php:D Y-m-d'],
        ],
        [
            'header' => 'Educator nr act',
            'content' => function($model){return $model->getEducatorActivitationsCount();}
        ],
        [
            'header' => 'Educator nr cond',
            'content' => function($model){return $model->getEducatorParticipantsCount();}
        ],

        [
            'header' => 'Psychologist nr act',
            'content' => function($model){return $model->getPsychologistActivitationsCount();}
        ],
        [
            'header' => 'Psychologist nr cond',
            'content' => function($model){return $model->getPsychologistParticipantsCount();}
        ],

        [
            'header' => 'Sociologist nr act',
            'content' => function($model){return $model->getSociologistActivitationsCount();}
        ],
        [
            'header' => 'Sociologist nr cond',
            'content' => function($model){return $model->getSociologistParticipantsCount();}
        ],

    ],
    'beforeHeader' => [
        [
            'columns' => [
                [],
                [],
                [
                    'content' =>   Module::t('labels','EDUCATOR_TITLE'),
                    'options' => [
                        'colspan' => 2,
                        'style' => 'text-align:center',

                    ]
                ],
                [
                    'content' =>   Module::t('labels','PSYCHOLOGIST_TITLE'),
                    'options' => [
                        'colspan' => 2,
                        'style' => 'text-align:center',

                    ]
                ],

                [
                    'content' =>   Module::t('labels','SOCIOLOGIST_TITLE'),
                    'options' => [
                        'colspan' => 2,
                        'style' => 'text-align:center',

                    ]
                ],
            ]
        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>
