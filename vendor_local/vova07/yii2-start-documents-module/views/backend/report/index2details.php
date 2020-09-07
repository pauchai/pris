<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\documents\models\backend\Report2Model
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","REPORT2_DETAILS");
$this->params['subtitle'] = $searchModel->getAttributeLabel(Yii::$app->request->get('query_name'));
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


<?php echo \kartik\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'person_id',
            'content' => function($model){
                $documentSearch = new \vova07\documents\models\backend\DocumentSearch();
                $documentSearch->person_id = $model->__person_id;
                $urlParams = ['DocumentSearch' => $documentSearch->getAttributes(['person_id'])];
                $urlParams[0] = '/documents/default/index';
                return \yii\helpers\Html::a($model->person->prisoner->getFullTitle(true),$urlParams);
                },

        ],
        'person.IDNP',
        'person.country.iso',
        [
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
        ]

    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>
