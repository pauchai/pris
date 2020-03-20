<div class="cells-search">
    <?php $form = \yii\bootstrap\ActiveForm::begin(
        [
         //    'layout' => 'inline',
            //'action' => ['index'],
            'method' => 'get'
        ])?>

    <?php echo $form->field($model,'sector_id')->widget(\kartik\widgets\Select2::class,
            [
                'data' => \vova07\prisons\models\Sector::getListForCombo(),
                'pluginOptions' => ['allowClear' => true],
                'options' => ['id'=>'sector_id',
                    'placeholder' => 'Select Sector...',
                    ]
            ]
    )?>
    <?php echo $form->field($model,'cell_id')->widget(\kartik\widgets\DepDrop::class,
        [
                'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,

            'data' => isset($model->sector)?$model->sector->getCellsForCombo():[],
            'pluginOptions' => [
                'depends'=>['sector_id' .  $model->primaryKey],
                'url'=>\yii\helpers\Url::to(['sector-cells']),

            ],

            'select2Options' => [
                    'pluginOptions' => [
                        'allowClear'=>true

                    ],
                'options' => [
                         'placeholder' => 'Select Cell...',
                ],
            ],


        ]
    )?>

    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton('Search',['class' => 'btn btn-primary'])?>


    </div>
    </div>
    <?php \yii\bootstrap\ActiveForm::end()?>
</div>