<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vova07\videos\models\WordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
    <?php foreach ($video->words as $word) :?>
    <span><?=$word->title?></span>,
<?php endforeach ?>



    <?php $form = \yii\bootstrap\ActiveForm::begin(
    [
        'action' => ['/videos/default/video-words', 'video_id' => $video->primaryKey],
        'layout' => 'inline','id'=>'words',
        'method' => 'POST',
        'options' => ['data' => ['pjax' => true]],

    ])?>
        <?=$form->field($newWord, "video_id")->hiddenInput()->label(false)?>
        <?=$form->field($newWord, "title")->hiddenInput()->label(false)?>



    <?php // echo \yii\bootstrap\Html::submitButton("SUMBIT")?>

    <?php \yii\bootstrap\ActiveForm::end()?>