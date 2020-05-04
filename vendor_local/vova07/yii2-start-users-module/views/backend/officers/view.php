<?php
use vova07\themes\adminlte2\widgets\Box;
use vova07\users\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\users\models\Officer
 */
$this->title = Module::t("default","OFFICER");
$this->params['subtitle'] = $model->person->fio;
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
       // 'renderBody' => false,
        //'options' => [
        //    'class' => 'box-primary'
        //],
        //'bodyOptions' => [
        //    'class' => 'table-responsive'
        //],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php $box->beginBody(['title']);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'person.fio',
        'person.birth_year',
        'company.title',
        'department.title',
        'rank',

    ]
])?>

<?php  $box->endBody()?>

<?php $box->beginFooter();?>

<?php $box->endFooter();?>
<?php Box::end();?>

<?php $box = Box::begin ([
    'title' => Module::t('default','AUTHENTICATION_INFO')
])?>
<?php if(($model->user)):?>
    <?php echo \yii\widgets\DetailView::widget([
        'model' => $model->user,
        'attributes' => [
            'username',

        ]
    ])?>


<?php endif;?>
    <a href="<?=\yii\helpers\Url::to(['default/create','__ident_id'=>$model->getPrimaryKey()])?>" type="button" class="btn btn-default btn-block"><?=Module::t('default','CREATE_AUTH_USER')?></a>
<?php Box::end();?>