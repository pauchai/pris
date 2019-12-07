<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\users\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\users\models\User
 */
$this->title = Module::t("default","USERS");
$this->params['subtitle'] = $model->username;
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
        'username',
        'email',

    ]
])?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::end()?>



<?php $box = \vova07\themes\adminlte2\widgets\Box::begin ([
    'title' => Module::t('default','PERSON_INFO')
])?>
<?php if(($model->person)):?>
    <?php echo \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',

        ]
    ])?>
<?php else:?>
    <?php
    $urlParam = $model->getPrimaryKey(true);
    $urlParam[0] = 'people/create';
    ?>
    <a href="<?=\yii\helpers\Url::to($urlParam)?>" type="button" class="btn btn-default btn-block"><?=Module::t('default','CREATE_PERSON_INFO')?></a>


<?php endif;?>
<?php \vova07\themes\adminlte2\widgets\Box::end();?>

