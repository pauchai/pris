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

    <div class="class="col-sm-2">
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
track.mode = 'showing';
track2 = video[0].textTracks[1],
track2.mode = 'showing';
 cues2 = track2.cues;
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

  cues2Indexed = {};
 for (var ii = 0; ii < cues2.length; ii++) {
      
     cues2[ii].align = "end";
     cues2[ii].size = "40";
     cues2[ii].line = 50;
     
     
     var timeStr = '' + cues2[ii].startTime + '';

     var startTimeArr = timeStr.split('.');
         timeStr = Number.parseInt(startTimeArr[0]/60) + ':' + startTimeArr[0] % 60 ; 
    cues2Indexed[timeStr] = cues2[ii];
  }
  for (var i in cues) {
         cue = cues[i];
        cue.id = i;
        
        cue.align = 'start';
        cue.size = "40";
       //cue.line = "100%";
        cue.onenter = cueEnter;
        cue.onexit = cueExit;
        var timeStr = '' + cue.startTime + '';
        var startTimeArr = timeStr.split('.');
         timeStr = Number.parseInt(startTimeArr[0]/60) + ':' + startTimeArr[0] % 60 ; 
        //$("#subtitles_container").append($("<a id='" + cue.id + "' class=cue href='#"+ cue.startTime +"'>"+ timeStr +"</a>"));
        //$("#subtitles_container").append($("<span> "+ cue.text +"</span>"));
        //$("#subtitles_container").append($("<br/>"));
        td1 = $('<td></td>').append($("<a id='" + cue.id + "' class=cue href='#"+ cue.startTime +"'>"+ timeStr +"</a>"));
        td2 = $('<td></td>').append($("<span> "+ cue.text +"</span>"));
        if (timeStr in cues2Indexed){
            td3 = $('<td>' + cues2Indexed[timeStr].text+'</td');
            }
         else { 
            td3 = $('<td></td');
            }
        tr = $("<tr></tr>").append(td1).append(td2).append(td3);
        
        
        tr.appendTo("#subtitles_container");
        
        //$("#subtitles_container").append($("<tr></tr>"))
        
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
           aNext = $("a.focused").parent().parent().next().find("a.focused");
           aNext.click();

        }
        if (e.keyCode == 37) {      
            //$("a.focused").prev().prev().prev().click();
           aPrev = $("a.focused").parent().parent().prev().find("a.focused");
           aPrev.click();
           
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


