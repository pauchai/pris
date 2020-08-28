<?php
/**
 * @var \vova07\videos\models\Video $model
 * @var \yii\web\View $this
 */
    $wordsJson = \yii\helpers\Json::encode($model->getWords()->select('title')->all());

    $assets = \vova07\videos\Asset::register($this);
    $this->title = $model->title;
    $this->params['subtitle'] = \vova07\videos\Module::t('default', 'VIDEO_SUBTITLE');

?>
<style type="text/css">
    #subtitles_container a.focused,
    #subtitles_container a:focus {

        border-style: solid;
        border-width: 1px ;
        border-color: black;

    }


     #subtitles_container tr:focus-within  {
        background-color: lightblue;

    }
    .word-selected{
        bacground-color: green;
    }
    .highlight {
        background-color: #fff34d;
        -moz-border-radius: 5px; /* FF1+ */
        -webkit-border-radius: 5px; /* Saf3-4 */
        border-radius: 5px; /* Opera 10.5, IE 9, Saf5, Chrome */
        -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* FF3.5+ */
        -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* Saf3.0+, Chrome */
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* Opera 10.5+, IE 9.0 */
    }

    .highlight {
        padding:1px 4px;
        margin:0 -4px;
    }

</style>
<p>
    <?php print_r($model->metadata['subtitles'])?>
</p>
<div class="row">

<div class="col-sm-10">
    <video crossorigin="anonymous" id="video" controls    style="width:100%" class="col-sm-8" >

        <source src="<?php echo $model->getFullSrcUrl()?>" type="<?php echo $model['type']?>">
        <?php foreach($model->metadata['subtitles'] as $index=>$subTitle):?>
        <track  label=<?=$subTitle['name']?> kind="subtitles" srclang="en" src="<?php echo $model->getFullSubTitleTrackUrl($index)?>" default>
        <?php endforeach;?>
    </video>
    <button id="video-speed-0_75">0.75</button>
    <button id="video-speed-1">normal</button>



    <div     style="height:200px;overflow-y: scroll">
        <table>
            <thead>

            </thead>
            <tbody id="subtitles_container">

            </tbody>
        </table>

    </div>

</div >

    <div class="col-sm-2">
    <?=\yii\bootstrap\Html::a("To Anki", ['export-anki','id'=>$model->id]);?>
    <?php \yii\widgets\Pjax::begin()?>
    <?php echo $this->render("video_words", ['newWord' => $newWord, 'video' => $model])?>
    <?php  \yii\widgets\Pjax::end()?>
</div>
</div>




<?php
$this->registerJs( <<< JS

    var video = new Video("video");
//video.init();



JS
, \yii\web\View::POS_READY) ;

?>


