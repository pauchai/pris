<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $newModel Committee
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\themes\adminlte2\widgets\Box;
use kartik\grid\GridView;
use yii\grid\SerialColumn;
use vova07\psycho\Module;

use vova07\tasks\models\Committee;

use vova07\humanitarians\models\HumanitarianPrisoner;



$this->title = Module::t("default","PSYCHO_CHARACTERISTICS_TITLE");
$this->params['subtitle'] = 'PSYCHO_CHARACTERISTICS_LIST_TITLE';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>


<table width="100%">
    <tr>
        <td width="70%">

        </td>
        <td width="30%">
            <p style="text-align: center;font-size:160%">
                "APROB"
            </p>

            <p style="text-align: right">
                ___________________________________________________________________________________
            </p>
            <br/>
            <p style="text-align: right">
                ___________________________________________________________________________________
            </p>
            <br/>
            <br/>
            <br/>
            <br/>

        </td>
    </tr>
</table>

<?php $box = Box::begin();?>

<?php echo $this->render('_search',['model' => $searchModel])?>
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'hover' => true,
    'filterModel' => $searchModel,
    //'floatHeader' => true,
    'columns' => [
        ['class' => SerialColumn::class],
        [
            'attribute' => '__person_id',
            'value' => 'fullTitle',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],


        ],
        [
            'attribute' => 'sector_id',
            'value' => 'sector.title',
            'filter' => \vova07\prisons\models\Sector::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_SECTORS'), 'class'=> 'no-print form-control', 'id' => null],

        ],
        /**
         * @property integer $risk_id
         * @property boolean $feature_violent
         * @property boolean $feature_self_torture
         * @property boolean $feature_sucide
         * @property boolean $feature_addiction_alcohol
         * @property boolean $feature_addiction_drug

         */
/*        [
            'header' => '',
            'content' => function($model){
                if ($model->characteristic){
                    if ($model->characteristic->risk_id >= \vova07\psycho\models\PsyCharacteristic::RISK_HIGH){
                        $options = ['class'=>'label label-danger'];
                    } else if($model->characteristic->risk_id == \vova07\psycho\models\PsyCharacteristic::RISK_MEDIUM) {
                        $options = ['class'=>'label label-warning'];
                    } else {
                        $options = ['class'=>'label label-success'];
                    }
                    return \kartik\helpers\Html::tag('span',  $model->characteristic->risk,$options);
                }


            },
        ],*/
        [
            'attribute' =>         'characteristic.feature_violent',

            'format'=> 'html',
            'content' => function($model){
                if ($model->characteristic){
                    $className = $model->characteristic->feature_violent?'fa fa-check':'';
                    return \yii\bootstrap\Html::tag('i','',['class'=>$className . ' text-success']);
                }

            }
        ],
        [
            'attribute' =>         'characteristic.feature_self_torture',

            'format'=> 'html',
            'content' => function($model){
                if ($model->characteristic){
                    $className = $model->characteristic->feature_self_torture?'fa fa-check':'';
                    return \yii\bootstrap\Html::tag('i','',['class'=>$className . ' text-success']);
                }

            }
        ],
        [
            'attribute' =>         'characteristic.feature_sucide',

            'format'=> 'html',
            'content' => function($model){
                if ($model->characteristic){
                    $className = $model->characteristic->feature_sucide?'fa fa-check':'';
                    return \yii\bootstrap\Html::tag('i','',['class'=>$className . ' text-success']);
                }

            }
        ],

        [
            'attribute' =>         'characteristic.feature_addiction_alcohol',

            'format'=> 'html',
            'content' => function($model){
                if ($model->characteristic){
                    $className = $model->characteristic->feature_addiction_alcohol?'fa fa-check':'';
                    return \yii\bootstrap\Html::tag('i','',['class'=>$className . ' text-success']);
                }

            }
        ],

        [
            'attribute' =>         'characteristic.feature_addiction_drug',

            'format'=> 'html',
            'content' => function($model){
                if ($model->characteristic){
                    $className = $model->characteristic->feature_addiction_drug?'fa fa-check':'';
                    return \yii\bootstrap\Html::tag('i','',['class'=>$className . ' text-success']);
                }

            }
        ],


    ],

    'beforeHeader' => [
        [
            'columns' => [
                [],
                [],
                [],
               /* [
                    'content' =>  Module::t('labels','RISC_LABEL'),
                    'options' => [

                        'style' => 'text-align:center',
                        'class' => 'label-danger'
                    ]
                ],*/


                [
                    'content' =>  Module::t('labels','CATEGORY {n}',['n' => 1]),
                    'options' => [
                        'colspan' => 1,
                        'style' => 'text-align:center',
                        'class' => 'label-warning'
                    ]
                ],

                [
                    'content' => Module::t('labels','CATEGORY {n}',['n' => 2]),
                    'options' => [
                        'colspan' => 2,
                        'style' => 'text-align:center',
                        'class' => 'label-warning'
                    ]
                ],
                [
                    'content' =>  Module::t('labels','CATEGORY {n}',['n' => 3]),
                    'options' => [
                        'colspan' => 2,
                        'style' => 'text-align:center',
                        'class' => 'label-warning'
                    ]
                ],
            ]
        ]
    ]
])?>




<?php  Box::end()?>

