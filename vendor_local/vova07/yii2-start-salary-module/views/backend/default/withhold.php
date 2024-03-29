<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $salaryIssue SalaryIssue
 */
use vova07\salary\Module;
use kartik\grid\GridView;
use vova07\salary\models\SalaryIssue;
use vova07\users\models\OfficerView;
use vova07\users\models\Person;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

//$this->title = Module::t("default","EVENTS_TITLE");
$this->params['subtitle'] = Module::t("default","SALARY_WITHHOLD");
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?=$this->render('_issueView',['model' => $salaryIssue])?>
<?php if (!$salaryIssue->isNewRecord): ?>

    <?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
        [
             'title' => $this->params['subtitle'],
            // 'buttonsTemplate' => '{create}'
        ]

    );?>

    <?php
    $columns =
        [
            ['class' => yii\grid\SerialColumn::class],
            [
                'attribute' => 'officer.rank.category',
                'group' => true,
                'groupedRow' => true,
                'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class


            ],
            [
               // 'attribute' => 'officer.person.fio',
                'attribute' => 'personView.fio',
                'format' => 'html',
                'content' =>  function($model,$index,$key){
                    $content = \yii\bootstrap\Html::a(
                        $model->officer->person->fio ,
                        ['/users/officers/view',
                            'id' => $model->officer_id,
                        ]);
                    ;

                    return $content;
                },


            ],


        ];
    $attributes = ['is_pension', 'amount_pension', 'amount_income_tax', 'amount_execution_list',
        'amount_labor_union', 'amount_sick_list', 'total', 'amount_card'];
    foreach ($attributes as $attribute){
        if ($attribute == 'is_pension')
            $editableType = \kartik\editable\Editable::INPUT_CHECKBOX;
        else
            $editableType = \kartik\editable\Editable::INPUT_TEXT;

        $columns[] = [
            'class' => kartik\grid\EditableColumn::class,

            'hAlign' => GridView::ALIGN_CENTER,

            'attribute' => $attribute,
            'refreshGrid' => false,
            'editableOptions' =>  function ($model, $key, $index)use ($editableType){
                return [
                    'inputType' => $editableType,
                    'valueIfNull' => 0,
                    'asPopover' => false,

                    'formOptions' => [
                        'action' => ['change-withhold-column']
                    ],
                    'pluginEvents' => [
                        "editableSuccess"=>"function(event, val, form, data) {
                             
                            $.each(data.attributes, (index, value) => {
                                if (value != null) {
                                var \$element = $('#' + 'salarywithhold-$index-' + index + '-cont');
                               \$element.find('.kv-editable-value').html(value);
                                 \$element.find('.kv-editable-input').val(value);
                                }
                                
                                
                            }); 
                           
                            }",

                    ],
                ];
            },

        ];
    }

    /* $columns[] =
         [

             'class' => \yii\grid\ActionColumn::class,
             'template' => '{update}{delete}',
             'visible' => !$this->context->isPrintVersion,
         ];*/


    $columns[] = [
        'class' => \kartik\grid\ActionColumn::class,
        'template' => '{delete}',
        'buttons'   => [
            'delete' =>  function ($url, $model) {
                $name = 'delete';
                $title = 'delete';

                //  $opts = "{$name}Options";
                $options = ['title' => $title, 'aria-label' => $title, 'data-pjax' => '0'];
                $item = isset($this->grid->itemLabelSingle) ? $this->grid->itemLabelSingle : Yii::t('kvgrid', 'item');

                $options['data-method'] = 'post';
                $options['data-confirm'] = Yii::t('kvgrid', 'Are you sure to delete this {item}?', ['item' => $item]);


                return \yii\bootstrap\Html::a('delete',
                    \yii\helpers\Url::to(['/salary/with-hold/delete','officer_id' => $model->officer_id , 'year' => $model->year, 'month_no' => $model->month_no]),
                    $options
                );
            }
        ]
    ];

    $columns[] = [

        'class' =>  \kartik\grid\CheckboxColumn::class,

    ];

    ?>

    <?php echo GridView::widget(['dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // 'formatter' => ['class' => \yii\i18n\Formatter::class,'nullDisplay' => ''],
        //'emptyCell' => '',

        // 'floatHeader' => true,
        'pjax' => true,
        'columns' => $columns


    ])?>

    <?php  \vova07\themes\adminlte2\widgets\Box::end()?>


<?php endif?>