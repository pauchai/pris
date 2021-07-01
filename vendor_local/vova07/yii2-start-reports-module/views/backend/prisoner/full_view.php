<?php


use vova07\reports\Module;
use yii\helpers\Html;
use kartik\grid\GridView;
/**
 * @var $this \yii\web\View
 * @var $presenter \vova07\reports\models\PrisonerFullViewPresenter
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","REPORT_PRISONER_FULL_VIEW");
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
        'title' => '',



    ]

);?>

<?php
    echo \yii\widgets\DetailView::widget(
        [
            'model' => $presenter->prisoner,
            'attributes' => [
                'person.fio',
                'person.nationality',
                'person.education',
                'person.speciality',
                'person.country.title',
                [
                    'attribute' => 'person.maritalState.status.title',
                    'label' => \vova07\socio\Module::t('labels', "MARITAL_STATUS_LABEL")
                ],
                'termStartJui',
                'termFinishJui',
                'termUdoJui',
                'article',
                [
                    'attribute' => 'term',
                    'value' => $presenter->prisoner->term . ' (' . $presenter->prisoner->termRemain . ')'
                ]
                //'termRemain'
             ]
        ]
    )
?>

<?php
    echo GridView::widget(
        [
            'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $presenter->getProgramPrisoner()]),
            'caption' => Module::t('default', 'PLAN_INDIVIDUAL_TITLE'),
            'layout' => "{items}\n{pager}",
            'showHeader' => false,
            'columns' => [
                'programTitle',
                [
                    'attribute' => 'programDatePlan',
                    'label' => ''
                ],
                [
                    'attribute' => 'statusContent',
                    'content' => function($model){
                        $content = '';
                        if (is_array($model['statusContent'])):
                            foreach ($model['statusContent'] as $statusContentItem):
                                $content .= Html::tag('p',
                                        Html::tag('span', $statusContentItem['label'], ['style' => ['font-weight'=>'bolder']]) . '  ' .
                                        Html::tag('span', $statusContentItem['value'])
                                    );
                            endforeach;

                         else :
                            $content = $model['statusContent'];
                        endif;
                         return $content;
                    }
                ]
            ]

        ]
    )
?>

<?php
echo GridView::widget(
    [
        'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $presenter->getEvents(), 'pagination'=> false]),
        'caption' => Module::t('default', 'EVENTS_EDUCATOR_TITLE'),
        'layout' => "{items}\n{pager}",
        'showHeader' => false,
        'columns' => [
            ['class' => \yii\grid\SerialColumn::class],
            'eventTitle',
            'eventDate'
        ]

    ]
)
?>

<?php
echo GridView::widget(
    [
        'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $presenter->getConcepts(), 'pagination'=> false]),
        'caption' => Module::t('default', 'CONCEPTS_TITLE'),
        'layout' => "{items}\n{pager}",
        'showHeader' => false,
        'columns' => [

            [
                'attribute' => 'conceptTitle',
                //'group' => true
            ],
            'years'
        ]


    ]
)
?>
<?php
echo GridView::widget(
    [
        'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $presenter->getJobs(), 'pagination'=> false]),
        'caption' => Module::t('default', 'JOBS_TITLE'),
        'layout' => "{items}\n{pager}",
        'showHeader' => false,
        'columns' => [
            [
                'attribute' => 'label',
                'group' => true,
            ],
            'years',
            'hours_totall'
        ]

    ]
)
?>

<?php
echo GridView::widget(
    [
        'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $presenter->getComittee(), 'pagination'=> false]),
        'caption' => Module::t('default', 'COMMITTEE_TITLE'),
        'layout' => "{items}\n{pager}",
        'showHeader' => false,

    ]
)
?>
<?php
echo GridView::widget(
    [
        'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $presenter->getDocuments(), 'pagination'=> false]),
        'caption' => Module::t('default', 'DOCUMENTS_TITLE'),
        'layout' => "{items}\n{pager}",
        'showHeader' => false,

    ]
)
?>

<?=Html::tag('p',
    Module::t('default','BALANCE_REMAIN_LABEL') . ":" . $presenter->getBalanceRemain(),
       ['class' => [
           'kv-table-caption',
           ($presenter->getBalanceRemain()<0)?'text-danger':'text-info'
           ]]
    )?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>

