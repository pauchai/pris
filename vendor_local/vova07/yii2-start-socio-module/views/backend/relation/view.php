<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\socio\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\socio\models\MaritalState
 */
$this->title = Module::t("default","RELATION");
$this->params['subtitle'] = $model->person->fio  ;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php
    $updateUrl = $model->getAttributes(['person_id', 'ref_person_id']);
    $updateUrl[0] = 'update';
    $deleteUrl = $model->getAttributes(['person_id', 'ref_person_id']);
    $deleteUrl[0] = 'delete';
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}',
        'buttons' => [
            'update' =>
            [
                'url' => $updateUrl,
                'icon' => 'fa-edit',
                'options' => [
                    'class' => 'btn-default no-print',
                    'title' => Yii::t('vova07/themes/admin/widgets/box', 'update')
                ]
            ],
            'delete' => [
                'url' => $deleteUrl,
                'icon' => 'fa-remove',
                'options' => [
                    'class' => 'btn-default no-print',
                    'title' => Yii::t('vova07/themes/admin/widgets/box', 'delete'),
                       'data-confirm' => Yii::t('vova07/themes/admin/widgets/box', 'Are you sure you want to delete this item?'),
                    'data-method' => 'delete'
                ]
            ]
        ]
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'person.fio',
        'refPerson.fio',
        'type.title',
        'document.type'

    ]
])?>


