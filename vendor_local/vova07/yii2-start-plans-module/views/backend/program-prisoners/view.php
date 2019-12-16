<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\plans\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\ProgramPrisoner
 * @var $newParticipant \vova07\plans\models\ProgramPrisoner
 */
$this->title = Module::t("default","PROGRAM");
$this->params['subtitle'] = $model->prisoner->fullTitle;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
                'attribute' => 'program_id',
            'format' => 'html',
            'value' => function($model){
                if ($model->program) {
                    $url = ['/plans/programs/view','id'=>$model->program->primaryKey];

                    return \yii\helpers\Html::a($model->program->programDict->title,
                        $url
                    );
                }
                 else
                    return null;

            }
        ],
        'prison.company.title',
        'markTitle',
        'plannedBy.fio',
        'date_plan',
        'status',


    ]
])?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::end()?>



