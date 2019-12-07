<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Cell
  */
$this->title = Module::t("default","PRISON_CELLS");
$this->params['subtitle'] = Module::t("default","CREATE");;
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}',
        'buttons' => [
            'cancel' => [
                'url' => ['index','sector_id' => $sectorModel->primaryKey],
                'icon' => 'fa-reply',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Create'),

                ]
            ]
        ]
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box,'sectorModel'=>$sectorModel])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>




`