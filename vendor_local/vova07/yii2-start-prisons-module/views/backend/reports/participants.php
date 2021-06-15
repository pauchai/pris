<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/11/20
 * Time: 9:02 AM
 */
use kartik\grid\GridView;
?>
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'person.fio',
            'content' => function($model) {return
                \yii\bootstrap\Html::a(\yii\helpers\ArrayHelper::getValue($model, 'person.fio'), ['/users/prisoner/view', 'id' => $model->__person_id]);
            }
        ],

        'person.birth_year',
        'sector.title',
        [
            'value' => 'termDateFromJournal'
        ]
    ]
])?>
