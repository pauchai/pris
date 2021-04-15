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




$this->title = Module::t("default","SOCIO_TITLE");
$this->params['subtitle'] = 'PSYCHO_DASHBOARD_TITLE';
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
    <?=Html::a(Module::t('default','MARITAL_STATUS_LABEL'),['/socio/marital-status/index'])?>
</p>
<p>
    <?=Html::a(Module::t('default','DISABILITY_LABEL'),['/socio/disability/index'])?>
</p>
<?php  Box::end()?>

