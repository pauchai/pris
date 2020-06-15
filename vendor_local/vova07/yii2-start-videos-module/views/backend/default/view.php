<?php
/**
 * @var \vova07\videos\models\Video $model
 * @var \yii\web\View $this
 */


    $assets = \vova07\videos\Asset::register($this);
    $this->title = $model->title;
    $this->params['subtitle'] = \vova07\videos\Module::t('default', 'VIDEO_SUBTITLE');

?>
<style type="text/css">
    #subtitles_container a.focused{

        border-style: solid;
        border-width: 1px ;
        border-color: black;
    }
    #subtitles_container a:focus {

        border-style: solid;
        border-width: 1px ;
        border-color: black;

    }
    #subtitles_container a.focused + span {
        background-color: yellow;

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
<div class="row">

<div class="col-sm-8">
    <video crossorigin="anonymous" id="video" controls    style="width:100%" class="col-sm-8" >

        <source src="<?php echo $model->getFullSrcUrl()?>" type="<?php echo $model['type']?>">

        <track  label="English" kind="subtitles" srclang="en" src="<?php echo $model->getFullSubTitleTrackUrl()?>" default>
    </video>
    <button id="video-speed-0_75">0.75</button>
    <button id="video-speed-1">normal</button>

    <?=\yii\bootstrap\Html::a("To Anki", ['export-anki','id'=>$model->id]);?>
    <?php \yii\widgets\Pjax::begin()?>
    <?php $wordsJson = \yii\helpers\Json::encode($words->select('title')->column())?>
    <?php foreach ($words->all() as $word) :?>
    <span><?=$word->title?></span>,
    <?php endforeach ?>



    <?php $form = \yii\bootstrap\ActiveForm::begin(['layout' => 'inline','id'=>'words',
        'options' => ['data' => ['pjax' => true]]
        ])?>
    <div id='row_for_clone' style="display:none">
        <?php $sampleWord = new \vova07\videos\models\Word()?>
        <?=$form->field($sampleWord, "[]title")?>
    </div>


    <?php echo \yii\bootstrap\Html::submitButton("SUMBIT",['id'=>'submit_words'])?>

    <?php \yii\bootstrap\ActiveForm::end()?>
    <button id="button_clone">CLONE</button>
    <?php \yii\widgets\Pjax::end()?>

</div >

    <div  id="subtitles_container"  class="class="col-sm-4" style="height:500px;overflow-y: scroll">

    </div>



<?php
$this->registerJs( <<< JS
    rowForCloneEl = $("#row_for_clone").clone().removeAttr('id')
    rowForCloneEl.find("input").each(function() {
        this.id="";
        this.value = "";
    });

var createNewWordHtml = rowForCloneEl.html(); 
$("#row_for_clone").remove();
var video=$("#video");

track  = video[0].textTracks[0],
cues    = track.cues;

cueEnter = function(){
    //var lineId = Number.parseFloat(this.startTime).toFixed(2)+Number.parseFloat(this.endTime).toFixed(2);
    //var regex = /\./gi
    //lineId = lineId.replace(regex,"");
    var lineId = this.id; 
    $("#"+lineId).focus();
    $("#"+lineId).addClass("focused");

    //console.log("#" + lineId + "enter");
}

cueExit = function(){
   //var lineId = Number.parseFloat(this.startTime).toFixed(2)+Number.parseFloat(this.endTime).toFixed(2);
    //var regex = /\./gi
    //lineId = lineId.replace(regex,"");
    var lineId=this.id; 
    $("#"+lineId).removeClass("focused");
    //console.log("#" + lineId + "exit");
  }

  
  for (var i in cues) {
        var cue = cues[i];
        cue.id = i;
        cue.onenter = cueEnter;
        cue.onexit = cueExit;
        var timeStr = '' + cue.startTime + '';
        var startTimeArr = timeStr.split('.');
         timeStr = Number.parseInt(startTimeArr[0]/60) + ':' + startTimeArr[0] % 60 ; 
        $("#subtitles_container").append($("<a id='" + cue.id + "' class=cue href='#"+ cue.startTime +"'>"+ timeStr +"</a>"));
        $("#subtitles_container").append($("<span> "+ cue.text +"</span>"));
        $("#subtitles_container").append($("<br/>"));
    }
   
function jumpToTime(time){
    video[0].currentTime = time;
}
$(".cue").on("click", function(e){
    e.preventDefault();
    //var timestamp = this.hash.match(/\d+$/,'')[0] * 1000;
    var timestamp = this.hash.match(/[\d\.]+$/,'')[0] ;
    jumpToTime(timestamp);
    return false;
    
});

function highlightWords(terms)
{
     
        // remove any old highlighted terms
        $('#subtitles_container').unhighlight();
        $('#subtitles_container').highlight(terms);
}

$("#video-speed-0_75").on("click", function(e){
     video[0].playbackRate = 0.75;
    return false;
});
$("#video-speed-1").on("click", function(e){
     video[0].playbackRate = 1;
    return false;
});

$(document).keydown(
    function(e)
    {    
        if (e.keyCode == 39) {      
            $("a.focused").next().next().next().click();

        }
        if (e.keyCode == 37) {      
            $("a.focused").prev().prev().prev().click();

        }
    }
);



$("#subtitles_container").on("mouseup", function (e) {
    var selected = getSelection();
    var range = selected.getRangeAt(0);
     var selText =  selected.toString();
    if(selText.length > 1){
        //var newNode = document.createElement("a");
        //newNode.setAttribute("class", "word-selected");
        
        //range.surroundContents(newNode);
        $('#subtitles_container').highlight(selText);
        
        
         var createdEl = createNewWordField();
        
        window.setTimeout(function(){
            inputEl = createdEl.find("input[name='Word[][title]']").last();
             inputEl.val(selText);     
                  }, 600);
        
        
    }
    selected.removeAllRanges();
 });

function getSelection() {
    var seltxt = '';
     if (window.getSelection) { 
         seltxt = window.getSelection(); 
     } else if (document.getSelection) { 
         seltxt = document.getSelection(); 
     } else if (document.selection) { 
         seltxt = document.selection.createRange().text; 
     } else return;
    return seltxt;
};

function createNewWordField()
{
    el = $("<div></div>").html(createNewWordHtml);
    el.insertBefore($("#submit_words"));
    return el
}

$("#button_clone").on("click",function(e){
    createNewWordField();
});

highlightWords($wordsJson);

JS
, \yii\web\View::POS_READY) ;

?>


