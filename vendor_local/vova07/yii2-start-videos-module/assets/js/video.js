
class Video {

    static  modeStateMax = 3;
    constructor(video_id){
        this.video_id = video_id;
        this.track = this.video.textTracks[0];
        this.track2 = this.video.textTracks[1];
        this.modeState = 0;

        this.cueEnter = (e) => {
            //var lineId = Number.parseFloat(this.startTime).toFixed(2)+Number.parseFloat(this.endTime).toFixed(2);
            //var regex = /\./gi
            //lineId = lineId.replace(regex,"");
            var lineId = e.currentTarget.id;
            $("#"+lineId).focus();
            $("#"+lineId).addClass("focused");
            this.modeState = 0;
            this.resolveModeState();
            //console.log("#" + lineId + "enter");
        };

        this.cueExit = (e) => {
            //var lineId = Number.parseFloat(this.startTime).toFixed(2)+Number.parseFloat(this.endTime).toFixed(2);
            //var regex = /\./gi
            //lineId = lineId.replace(regex,"");
            var lineId=e.currentTarget.id;
            $("#"+lineId).removeClass("focused");
            //console.log("#" + lineId + "exit");
        };

         this.cueClickHandler = (e) => {
             e.preventDefault();
             //var timestamp = this.hash.match(/\d+$/,'')[0] * 1000;
             var timestamp = e.currentTarget.hash.match(/[\d\.]+$/,'')[0] ;
             this.jumpToTime(timestamp);
             return false;
         };

         this.cueAClickHandler = e => {
            e.preventDefault();
             e.currentTarget.querySelector('.cue').click();

             return false;
         };

        this.handlerVideoPlayBackRate075 = (e) => {
          this.video.playbackRate = 0.75;
        };

        this.handlerVideoPlayBackRate1 = (e) => {
            this.video.playbackRate = 1;
        };


         this.handlerKeyDown = (e) => {
             /**
              * @var document Document
              */
             var aNext, aPrev;
               if (e.keyCode == 17)
                     return;
                 if (e.keyCode == 39) {
                   //  aNext = $("a.focused").parent().parent().next().find("a");
                     aNext = document.querySelector('a:focus').parentElement.parentElement.nextElementSibling.querySelector('a.cue');
                     aNext.click();

                 }
                 if (e.keyCode == 37) {
                     //$("a.focused").prev().prev().prev().click();
                     aPrev = document.querySelector('a:focus').parentElement.parentElement.previousElementSibling.querySelector("a.cue");
                     aPrev.click();

                 }
                 if (e.keyCode == "R".charCodeAt(0)){
                     aPrev = document.querySelector('a:focus')
                     aPrev.click();
                     this.incModeState();

                     if (this.video.paused )
                         this.video.play();
                 }
                 if (e.keyCode == 32) {

                     if (this.video.paused || this.video.ended) {
                         this.video.play();
                     }
                     else {
                         this.video.pause();
                     }

                 }


         };

        this.handlerSubtitlesContainerMouseUp = (e) => {

                var selected = this.getSelection(),
                    //range = selected.getRangeAt(0),
                    selText =  selected.toString();


                if(selText.length > 1){
                    //var newNode = document.createElement("a");
                    //newNode.setAttribute("class", "word-selected");
                    var r = window.confirm("add to dictionary");
                    if (r == true) {
                        $('#subtitles_container').highlight(selText);
                        $('input#videowordform-title').val(selText);
                        $('form#words').submit();
                        selected.removeAllRanges();
                    }
                    //range.surroundContents(newNode);



                }
                return false;


        };
        this.video.onloadeddata = (e) => {
            this.resolveModeState()
            this.init();

        };
    }





    resolveModeState()
    {

        if (this.modeState == 0){
            this.track.mode = 'hidden';
            this.track2.mode = 'hidden';
            this.video.playbackRate = 1;
        } else if (this.modeState == 1){
            this.video.playbackRate = 1;
           this.track.mode = 'showing';
           this.track2.mode = 'hidden';
        }  else if (this.modeState == 2){
            this.video.playbackRate = 0.75;
            this.track.mode = 'showing';
            this.track2.mode = 'showing';
        }
    }
    incModeState()
    {
        this.modeState++;

        if (this.modeState > Video.modeStateMax)
            this.modeState = 0;

        this.resolveModeState();
    }

    /**
     *
     * @returns {HTMLElement | null}
     */
    get video(){
        /**
         * @var document Document
         */
        return document.getElementById(this.video_id);
    }



    init(){
        /**
         *
         * @var document Document
         */
        var cues2Indexed = {}, cue2, cue, timeStr, startTimeArr, td3, td2, td1, tr;


//for (var ii  = 0; ii < cues2.length; ii++) {
        for (var ii = 0; ii <  this.track2.cues.length; i++) {
            cue2 = this.track2.cues[ii];
            // cue2.align = "left";
            // cue2.size = "48";
            cue2.line = 0;
            //cues2[ii].line = 50;


            timeStr = '' + cue2.startTime + '';
            startTimeArr = timeStr.split('.');
            timeStr = Number.parseInt(startTimeArr[0]/60) + ':' + startTimeArr[0] % 60 ;
            cues2Indexed[timeStr] = cue2;
        }
        for (var i = 0; i< this.track.cues.length; i++) {
            cue = this.track.cues[i];
            cue.id = i;


            //e.line = 10';
            //cue.align = 'right';
            //cue.size = "48";
          //  cue.line = -1;
            cue.onenter = this.cueEnter;
            cue.onexit = this.cueExit;
            timeStr = '' + cue.startTime + '';
            startTimeArr = timeStr.split('.');
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
                td3 = $('<td></td>');
            }
            tr = $("<tr></tr>").append(td1).append(td2).append(td3);


            tr.appendTo("#subtitles_container");


            //$(".cue").on("click", this.cueClickHandler);

            document.querySelectorAll('.cue').forEach(cue=>cue.addEventListener('click', this.cueClickHandler));

            document.querySelectorAll('a.cue').forEach(cue=>cue.parentElement.parentElement.addEventListener('click', this.cueAClickHandler));
            //$("a.cue").parent().parent().on("click", this.cueAClickHandler);
            //$("#subtitles_container").append($("<tr></tr>"))


            document.getElementById("video-speed-0_75").addEventListener("click", this.handlerVideoPlayBackRate075);
            document.getElementById("video-speed-1").addEventListener("click", this.handlerVideoPlayBackRate1);

            document.addEventListener('keydown', this.handlerKeyDown);
            //$(document).keydown(this.handlerKeyDown);

            document.querySelectorAll('#subtitles_container span').forEach(span=>span.addEventListener("mouseup",  this.handlerSubtitlesContainerMouseUp));

        }

    }

    jumpToTime(time){
        this.video.currentTime = time;
    }


    highlightWords(terms)
    {

        // remove any old highlighted terms
        $('#subtitles_container').unhighlight();
        $('#subtitles_container').highlight(terms);
    }

    getSelection() {
        var seltxt = '';
        if (window.getSelection) {
            seltxt = window.getSelection();
        } else if (document.getSelection) {
            seltxt = document.getSelection();
        } else if (document.selection) {
            seltxt = document.selection.createRange().text;
        } else return;
        return seltxt;
    }







}