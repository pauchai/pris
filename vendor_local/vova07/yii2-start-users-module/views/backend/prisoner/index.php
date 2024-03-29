<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
//use yii\grid\GridView;
use kartik\grid\GridView;
use vova07\users\models\Prisoner;
use vova07\users\models\backend\PrisonerViewSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","PRISONERS");
$this->params['subtitle'] = Module::t("default","SUBTITLE_LIST");
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>
<?php echo $this->render('_search',['model' => $searchModel])?>

<?=\kartik\helpers\Html::a($isLight?\vova07\site\Module::t('default','LIST_VERSION_FULL'):\vova07\site\Module::t('default','LIST_VERSION_LIGHT'),\yii\helpers\Url::current(['isLight' => !$isLight]),['class'  => 'btn btn-info no-print'])?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [

            'attribute' => 'fio',

            //'filter' => Prisoner::getListForCombo(),
            //'filterType' => GridView::FILTER_SELECT2,
           // 'filterWidgetOptions' => [
           //     'attribute' => '__person_id',
           //     'pluginOptions' => ['allowClear' => true ],
           // ],
           // 'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],

            //'header' => '',
           'content' => function($model){return Html::a($model->person->getFio(false,true), ['view','id' => $model->primaryKey]);},
           //
           //  'value' => 'person.fio'
        ],
        //'person.fio',
        //'person.birth_year',
 /*       [
            'attribute'  => 'prison_id',
            'header' => '',
            'value' => 'prison.company.title',
            'filter' => \vova07\prisons\models\Prison::getListForCombo(),

        ],*/
        [
            'class' => kartik\grid\EditableColumn::class,
            'attribute'  => 'sector_id',


            'editableOptions' =>  function ($model, $key, $index){

                return [

                    'inputType' => \kartik\editable\Editable::INPUT_DEPDROP,
                    'formOptions' => [
                        'action' => ['edit-sector']
                    ],

                    'beforeInput' => function ($form, $widget) use ($model, $index) {

                        return $form->field($model, "[$index]prison_id")->widget(\kartik\widgets\Select2::class, [
                            'data' => \vova07\prisons\models\Prison::getListForCombo($model->prison_id),
                            'pluginOptions'=>['allowClear'=>true],

                            'options' => [
                                'id' => 'prison_id' .  $model->primaryKey,
                                'placeholder' => 'Select Prison...'

                            ],

                        ]);
                    },

                    'options' => [

                        'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
                        'select2Options'=> ['pluginOptions'=>['allowClear'=>true]],
                        'options'=>['id' => 'sector_id' . $model->primaryKey,'placeholder'=>'Enter Prison...'],
                        'pluginOptions'=>[
                            'depends'=>['prison_id' .  $model->primaryKey],
                            'url'=>\yii\helpers\Url::to(['prison-sectors'])
                        ],
                        'data' => \vova07\prisons\models\Sector::getListForCombo()
                    ],
                    'afterInput' => function ($form, $widget) use ($model, $index) {

                        return
                            $form->field($model, "[$index]cell_id")->widget(\kartik\depdrop\DepDrop::class, [
                                'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
                                'data' => \vova07\prisons\models\Cell::getListForCombo($model->sector_id),
                                'pluginOptions'=>[
                                    'depends'=>['sector_id' .  $model->primaryKey],
                                    'url'=>\yii\helpers\Url::to(['sector-cells']),

                                    'allowClear'=>true
                                ],

                                'options' => [
                                    'placeholder' => 'Select Cell...',
                                    //'prompt' => 'tt',


                                ],

                            ]) .
                            $form->field($model, "[$index]status_id")->widget(\kartik\widgets\Select2::class, [
                            'data' => \vova07\users\models\Prisoner::getStatusesForCombo(),
                            'pluginOptions'=>['allowClear'=>true],

                            'options' => [
                                    'placeholder' => 'Select Status...',
                                //'prompt' => 'tt',


                            ],

                        ]);
                    },
                ];
                },

            'header' => '',
            'value' => 'sector.title',
            'filter' => \vova07\prisons\models\Sector::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_SECTORS'), 'class'=> 'no-print form-control', 'id' => null],

        ],
        [
            'visible' => $isLight === false,
            'attribute' => 'status_id',
            'content' => function($model){
                if ($model->status_id > \vova07\users\models\Prisoner::STATUS_ACTIVE){
                    $options = ['class'=>'label label-danger'];
                } else if($model->status_id == \vova07\users\models\Prisoner::STATUS_ACTIVE) {
                    $options = ['class'=>'label label-success'];
                } else {
                    $options = ['class'=>'label label-info'];
                }
                return Html::tag('span',  $model->status,$options);
            },
            //contentOptions' => ['class' => '.no-print'],
            'hidden' => $this->context->isPrintVersion,
            'filter' => \vova07\users\models\Prisoner::getStatusesForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' =>  Module::t('default','SELECT_STATUS_FILTER'), 'class'=> 'form-control', 'id' => null],

        ],
        [
            'attribute' => 'person.IDNP',
            'visible' => $isLight === false,
        ],

        //'person.buletin.seria',
        //'person.buletin.type',
        //'prison.company.title',

        //'sector.title',
        [
            'visible' => $isLight === false,
            'header' => \vova07\users\Module::t('label','DOCUMENTS_TITLE'),
            'content' => function($model){
                $content = '';
                foreach($model->person->identDocs as $doc)
                {

                    $content .= Html::tag('span', $doc->type,['class'=>' label label-success ']);
                    $content .= ' ' . Html::tag('span', $doc->seria,['class'=>'  label label-success']);
                    if ($doc->isExpired()){
                        $content .= Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>' label label-danger']);
                    } else {
//                        $content = Html::tag('span', $content,['class'=>'label label-success']);
                        if ($doc->isAboutExpiration()){
                            $content .= ' ' .Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>'label label-warning']);

                        }
                    };
                    $content .= "<br/>";
                }
             return $content;

            }
        ],

        [
            'header' => '',
            'value' => 'person.country.iso',
        ],
        [
            'visible' => $isLight === false,
            'attribute' => 'person.address',
        ],

        [

          'attribute' => 'article',
           'visible' => $isLight === true,

        ],

        [


            'attribute' => 'term_start',
            'value' => 'termStartJui',

            'visible' => $isLight === true,
            //'filterType' => GridView::FILTER_DATE,
            'filter' => \kartik\widgets\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'termStartFromJui',
                'attribute2' => 'termStartToJui',
                'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => [
                    'allowClear' => true,
                    'format' => 'dd-mm-yyyy']
            ])
        ],
        [



            'attribute' => 'term_finish',
            'content' => function($model){
                //$content = Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>' label label-danger']);

                    $currDate = new DateTime();
                    $style = ['class' => 'label label-default'];
                    $value = null;
                    if ($model->term_finish) {
                        $dateTermFinish = new DateTime($model->term_finish);
                        $daysRemain = $currDate->diff($dateTermFinish)->format('%R%a');
                        $value = $model->termFinishJui;

                        if ($daysRemain <= 30){
                            $style = ['class' => 'label label-danger'];
                            $value = $value . ' ' . Yii::$app->formatter->asRelativeTime($value);
                        }
                        elseif ($daysRemain >=30 && $daysRemain <= 30*6)
                            $style = ['class' => 'label label-info'];

                        elseif ($daysRemain >=30*6 && $daysRemain <= 30*12)
                            $style = ['class' => 'label label-success'];
                    }
                  //  $value = $dateTermFinish->format('d-m-Y') . ' ' . $daysRemain;




                $content = Html::tag('span', $value, array_merge($style, ['style' => 'font-size:100%']));
                $content .= Html::tag('span', '(' . \yii\helpers\ArrayHelper::getValue($model,'term') .')', array_merge($style, ['style' => 'font-size:100%']));

                return $content;
            },
            'value' => 'termFinishJui',
            'visible' => $isLight === true,

            'filter' => \kartik\widgets\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'termFinishFromJui',
                'attribute2' => 'termFinishToJui',
                'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => [
                    'allowClear' => true,
                    'format' => 'dd-mm-yyyy']
            ])
        ],

        [


            'attribute' => 'term_udo',
            'content' => function($model){
                //$content = Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>' label label-danger']);

                $currDate = new DateTime();
                $style = ['class' => 'label label-default'];
                $value = null;
                if ($model->term_udo) {
                    $dateTermUdo = new DateTime($model->term_udo);
                    $daysRemain = $currDate->diff($dateTermUdo)->format('%R%a');
                    $value = $model->termUdoJui ;

                    if ($daysRemain <=0 ){
                        $style = ['class' => 'label label-danger'];
                        $value = $value . ' ' . Yii::$app->formatter->asRelativeTime($value);
                    }
                    elseif ($daysRemain >0 && $daysRemain <= 30*6)
                        $style = ['class' => 'label label-info'];
                    elseif ($daysRemain >=30*6 && $daysRemain <= 30*12)
                        $style = ['class' => 'label label-success'];
                }
                //  $value = $dateTermFinish->format('d-m-Y') . ' ' . $daysRemain;




                $content = Html::tag('span', $value, array_merge($style, ['style' => 'font-size:100%']));
              ///  $content .= Html::tag('span','(' . $model->calculatedUDO . ')', array_merge($style, ['style' => 'font-size:100%']));

                return $content;
            },
            'visible' => $isLight === true,

            'filter' => \kartik\widgets\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'termUdoFromJui',
                'attribute2' => 'termUdoToJui',
                'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => [
                    'allowClear' => true,
                    'format' => 'dd-mm-yyyy']
            ])
        ],


        [
            'hidden' => $this->context->isPrintVersion,

            'header' => '',
            'content'=> function($model){return Html::img($model->person->photo_preview_url,['class'=>"img-circle img-sm"]);},
        ],



        ['class' => \kartik\grid\ActionColumn::class,
            'dropdown' => true,
            'visibleButtons' => [
              'update' => Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONERS_UPDATE),
              'delete' => Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONERS_DELETE),
              'view' => Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONERS_VIEW)
            ],
            'updateOptions' => ['class' => 'disabled'],
            'hidden' => $this->context->isPrintVersion ,


        ],
    ]
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end()?>


