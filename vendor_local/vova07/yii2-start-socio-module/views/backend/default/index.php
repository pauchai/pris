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
use vova07\socio\Module;
use yii\helpers\Html;
use kartik\grid\GridView;
use  \yii\helpers\ArrayHelper;
use vova07\base\helpers\HtmlExtra;



$this->title = Module::t("default","SOCIO_TITLE");
$this->params['subtitle'] = 'SOCIO_DASHBOARD_TITLE';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php $box = Box::begin();?>

<p>
    <?=Html::a(Module::t('default','RELATIONS_LABEL'),['/socio/relation/index'])?>
</p>
<p>
    <?=Html::a(Module::t('default','MARITAL_STATUS_LABEL'),['/socio/marital-state/index'])?>
</p>
<p>
    <?=Html::a(Module::t('default','DISABILITY_LABEL'),['/socio/disability/index'])?>
</p>
<?php  Box::end()?>



<?=GridView::widget([
        'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [

        [
                'attribute' => 'fio',


            'group' => true,
            //'groupedRow' => true,
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

            'groupFooter' => function ($model){

                $addButton = Html::a(Module::t('labels','CREATE_RELATION'), ['create-relation', 'person_id' => $model->__person_id],
                    ['class' => "btn btn-default btn_post_new btn-xs"]);

                return [
                    //'mergeColumns' => [[2,3]], // columns to merge in summary

                    'content' => [             // content to show in each summary cell

                        3 => $addButton,
                        //   3 => GridView::F_SUM,


                    ],
                    'contentFormats' => [      // content reformatting for each summary cell
                        //  2 => ['format' => 'number', 'decimals' => 2],//   5 => ['format' => 'number', 'decimals' => 0],
                        //    3 => ['format' => 'number', 'decimals' => 2],
                        //3 => ['format' => 'number', 'decimals' => 2],
                        //4 => ['format' => 'number', 'decimals' => 2]
                    ],
                    'contentOptions' => [      // content html attributes for each summary cell
                        // 2 => ['style' => 'text-align:right'],
                        //3 => ['style' => 'text-align:center'],

                    ],
                    // html attributes for group summary row
                    'options' => ['class' => 'info table-info','style' => 'font-weight:bold;']
                ];},

        ],

        'refPerson.fio',
       // 'relationType.title',
      //  'maritalStatus.title',

        [
            'header' => '',
            'content' => function($model){

                $relation = ArrayHelper::getValue($model, 'personRelation');
                /**
                 * @var $relation \vova07\socio\models\Relation
                 */
                if ($relation){
                    $relUrl = $relation->getAttributes(['person_id', 'ref_person_id']);
                    $relUrl[0] = '/socio/relation/view';

                    return  HtmlExtra::getLabelStatusAndDocument(
                        $relation->type->title,
                        $relUrl,
                        $relation->document
                    );
                }

            }
        ],
        [
            'attribute' => 'marital_status_id',
            'filter' => \vova07\socio\models\MaritalStatus::getListForCombo(),

            //'header' => '',
            'content' => function($model){
                $maritalState = ArrayHelper::getValue($model, 'maritalState');
                if ($maritalState) {
                    return  HtmlExtra::getLabelStatusAndDocument(
                        $maritalState->status->title,
                        ['/socio/marital-state/view', 'id' => $maritalState->primaryKey],
                        $maritalState->document
                    );
                } else {

                    return  \yii\bootstrap\Html::a(Module::t('default', 'CREATE_STATE'), ['/socio/marital-state/create', 'person_id' => $model->__person_id, 'ref_person_id' => $model->ref_person_id]);
                }



            }
        ],
    ]
])
?>