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
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;

use vova07\tasks\models\Committee;

use vova07\humanitarians\models\HumanitarianPrisoner;

$this->title = \vova07\plans\Module::t("default","HUMANITARIANS_TITLE");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => SerialColumn::class],
        'sector.title',
        'officer.person.fio',
        'dateIssueJui',
        HumanitarianPrisoner::GOOD_SOAP_LAUNDRY,
        HumanitarianPrisoner::GOOD_SOAP,
        HumanitarianPrisoner::GOOD_SHAVING_SET,
        HumanitarianPrisoner::GOOD_TOOTH_BRUSH,
        HumanitarianPrisoner::GOOD_TOOTH_PAST,
        HumanitarianPrisoner::GOOD_TOILET_PAPER,
        ['class' => ActionColumn::class]
    ]
])?>


<?= $this->render('_form',['model' =>$newModel,'box' => $box,'params' => ['action' => ['create'], 'layout' => 'inline']])?>

<?php  Box::end()?>

