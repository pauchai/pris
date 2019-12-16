<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\plans\Module;
/**
 * @var $this \yii\web\View
 * @var $event \vova07\plans\models\Event
 * @var $newParticipant \vova07\plans\models\EventParticipant
 */
$dataProvider = new \yii\data\ActiveDataProvider(['query' => $event->getEventParticipants()]);
$this->title = Module::t("default","EVENT_PARTICIPANT");
$this->params['subtitle'] = $event->title;
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
    'model' => $event,
    'attributes' => [
            'prison.company.title',
        'title',
        'dateStartJui',
        'dateFinishJui',
        'status'

    ]
])?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::end()?>


<?php \yii\widgets\Pjax::begin()?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => Module::t("default","EVENT_PARTICIPANTS"),

    ]
);?>


<?php $gridColumns = [
    ['class' => yii\grid\SerialColumn::class],
    [
        'attribute' => 'prisoner.fio',
        'value' => function($model){return $model->prisoner->getFullTitle(true);}
    ],

    ]
?>

<?php echo \yii\grid\GridView::widget(['id' => 'participants','dataProvider' => $dataProvider,
    'columns' => $gridColumns,
])?>


<?php echo \common\widgets\Alert::widget()?>


<?php $form = ActiveForm::begin(
       [
               'id' => 'add-participant',
   // 'action' => ['add-participant'],
    'options' => [
            'data'=>['pjax'=>true]
    ]
    ]);
?>
<table >
    <thead>
    <tr>

        <th><?php echo \vova07\plans\Module::t('events','PRISONER_LABEL')?></th>

    </tr>

    </thead>
    <tbody>
    <tr>
        <td><?=$form->field($newParticipant,'prisoner_id')->label(false)->dropDownList(\vova07\users\models\Prisoner::getListForCombo(),['prompt'=>\vova07\plans\Module::t('events','SELECT_PRISONER')])?></td>

    </tr>
    </tbody>
</table>


<?php $box->beginFooter()?>

<?php echo \yii\bootstrap\Html::submitButton(Module::t('events','SUBMIT'))?>
<?php $box->endFooter()?>
<?php ActiveForm::end()?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::end()?>
<?php \yii\widgets\Pjax::end()?>


<?php $this->registerJs(<<<JS
   $("body").on('change','#add-participant select ', function(e){
      // alert('test');
       $(this).parents('form').submit();
   }) ;
JS
);
