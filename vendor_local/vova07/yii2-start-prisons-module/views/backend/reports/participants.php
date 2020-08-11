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
        'person.fio'
    ]
])?>
