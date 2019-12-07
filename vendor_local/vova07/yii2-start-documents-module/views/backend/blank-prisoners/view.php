<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\documents\models\BlankPrisoner
 */
$this->title = Module::t("default","Blanks");
$this->params['subtitle'] = $model->blank->title;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php
//$urlParams = $model->toArray(['blank_id','prisoner_id']);
$updateUrlParams = $model->primaryKey;
$updateUrlParams[0] = 'update';
$delUrlParams = $model->primaryKey;
$delUrlParams[0] = 'delete';
$printUrlParams = $model->primaryKey;
$printUrlParams[0] = '';
$printUrlParams['print']=true;


?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $model->prisoner->person->fio,
        'buttonsTemplate' => '{print}{update}{delete}',
        'buttons' => [
            'print' => [
                'url' => $printUrlParams,
                'icon' => 'fa-print',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'PRINT'),

                ]
            ],
            'update' => [
                'url' => $updateUrlParams,
                'icon' => 'fa-edit',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Update'),

                ]
            ],
            'delete' => [
                'url' => $delUrlParams,
                'icon' => 'fa-trash',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Update'),

                ]
            ],


        ]

    ]
);?>

<?=$model->content?>
<?php \vova07\themes\adminlte2\widgets\Box::end();?>


