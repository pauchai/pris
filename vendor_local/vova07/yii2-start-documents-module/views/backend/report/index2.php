<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\documents\Module;
use kartik\grid\GridView;
use vova07\documents\models\backend\Report2Model;
/**
 * @var $this \yii\web\View
 * @var $model Report2Model
 * @var $dataProvider \yii\data\ActiveDataProvider
 *
 */
$this->title = Module::t("default","REPORT_INDEX2");
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

    ]

);?>
<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'activePrisoners',
            'format' => 'html',
            'value' =>Html::a($model->getActivePrisoners()->count(),['/documents/report/index2-details', 'query_name' => 'activePrisoners'])
        ],
        [
            'attribute' => 'prisonersWithLegalDocuments',
            'format' => 'html',
            'value' =>Html::a($model->getPrisonersWithLegalDocuments()->count(),['/documents/report/index2-details', 'query_name' => 'prisonersWithLegalDocuments'])
        ],
        [
            'attribute' => 'prisonersWithExpiredDocuments',
            'format' => 'html',
            'value' =>Html::a($model->getPrisonersWithExpiredDocuments()->count(),['/documents/report/index2-details', 'query_name' => 'prisonersWithExpiredDocuments'])
        ],
        [
            'attribute' => 'prisonersForeignersAndStateless',
            'format' => 'html',
            'value' =>Html::a($model->getPrisonersForeignersAndStateless()->count(),['/documents/report/index2-details', 'query_name' => 'prisonersForeignersAndStateless'])
        ],
        [
            'attribute' => 'prisonersLocalWithPassport',
            'format' => 'html',
            'value' =>Html::a($model->getPrisonersLocalWithPassport()->count(),['/documents/report/index2-details', 'query_name' => 'prisonersLocalWithPassport'])
        ],
        [
            'attribute' => 'prisonersWithDocumentInProcess',
            'format' => 'html',
            'value' =>Html::a($model->getPrisonersWithDocumentInProcess()->count(),['/documents/report/index2-details', 'query_name' => 'prisonersWithDocumentInProcess'])
        ],

        [
            'attribute' => 'prisonersWithNotEnoughBalanceForDocument',
            'format' => 'html',
            'value' =>Html::a($model->getPrisonersWithNotEnoughBalanceForDocument()->count(),['/documents/report/index2-details', 'query_name' => 'prisonersWithNotEnoughBalanceForDocument'])
        ],


    ]
]);
?>


<?php \vova07\themes\adminlte2\widgets\Box::end()?>
