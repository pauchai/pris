<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prisoner
 */
$this->title = Module::t("default","PRISONER");
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
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php $box->beginBody();?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'person.photo_url:image',
        'status',
        'person.first_name',
        'person.second_name',
        'person.patronymic',
        'person.birth_year',
        'person.address',
        'article',
        'termStartJui',
        'termFinishJui',
        'termUdoJui',
        'prison.company.title',


        'sector.title',
        'cell.number'

    ]
])?>

<?php  $box->endBody()?>

<?php $box->beginFooter();?>

<?php $box->endFooter();?>

<?php \vova07\themes\adminlte2\widgets\Box::end()?>


<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><?=Module::t('default','ASISTENȚA_SOCIALĂ')?></h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                <i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">

        <div class="row">
            <?php
            $blankPrisonerSearchModel = new \vova07\documents\models\backend\BlankPrisonerSearch();
            $blankPrisonerSearchParamName = $blankPrisonerSearchModel->formName();
            ?>
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['/documents/blank-prisoners/index' , $blankPrisonerSearchParamName=>['prisoner_id'=>$model->primaryKey]])?>">
                    <i class="fa  fa-file-text-o"></i> <?=Module::t('default','@NEVOE_RISCURI')?></a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['documents'])?>">
                    <i class="fa fa-venus-mars"></i> <?=Module::t('default','@STARIA_CIVILA')?></a>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['/plans/default/index','prisoner_id'=>$model->primaryKey])?>">
                    <i class="fa  fa-map-o"></i> <?=Module::t('default','@PLANUL INDIVIDUAL DE EXECUTAREA PEDEPSEI')?></a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['documents'])?>">
                    <i class="fa fa-wheelchair"></i> <?=Module::t('default','@GRAD DE DEZABILITATE')?></a>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['documents'])?>">
                    <i class="fa fa-calendar-check-o"></i> <?=Module::t('default','@PROGRAME OBLIGATORII')?></a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['documents'])?>">
                    <i class="fa fa-folder-open-o"></i> <?=Module::t('default','@ACHITĂRI SOCIALE')?></a>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['documents'])?>">
                    <i class="fa  fa-calendar"></i> <?=Module::t('default','@ACTIVITĂȚI OPȚIONALE')?></a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['documents'])?>">
                    <i class="fa  fa-share-alt"></i> <?=Module::t('default','@SUPORT SOCIAL')?></a>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['/prisons/documents', 'DocumentSearch[person_id]'=>$model->primaryKey])?>">
                    <i class="fa fa-barcode"></i> <?=Module::t('default','DOCUMENTS_IDENTIFICATION')?></a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-default btn-block" href="<?=\yii\helpers\Url::to(['documents'])?>">
                    <i class="fa fa-calendar-plus-o"></i> <?=Module::t('default','@ALTE_ACTIVITI')?></a>
            </div>

        </div>


    </div>
    <!-- /.box-body -->
    <div class="box-footer">

    </div>
    <!-- /.box-footer-->
</div>