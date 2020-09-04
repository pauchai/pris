
class Video {

    static get  modeStateMax(){ return 3; }

    /**
     *
     * @returns {HTMLElement | null}
     */
    get video(){
        /**
         * @var document Document
         */
        return this.getVideo();
    }

    constructor(video_id){
        this.video_id = video_id;
        this.video.textTracks[0].mode = 'showing';
        this.track = this.video.textTracks[0];
        this.video.textTracks[1].mode = 'showing';
        this.track2 = this.video.textTracks[1];
        this.modeState = 0;
        this.cuesIndexed = [];
        this.lastActiveCueId;
        this.video.onloadeddata = (e) => {
            this.resolveModeState()
            this.init();

        };
        this.attachHandlers();


    }

    attachHandlers(){

        this.cueEnter = (e) => {
            //var lineId = Number.parseFloat(this.startTime).toFixed(2)+Number.parseFloat(this.endTime).toFixed(2);
            //var regex = /\./gi
            //lineId = lineId.replace(regex,"");

            var lineId = Number.parseInt(e.currentTarget.id);
            this.lastActiveCueId = lineId;
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

            var aNext, aPrev, nextCue, prevCue;

            if (e.keyCode == 17)
                return;
            if (e.keyCode == 39) {

                //  aNext = document.querySelector('a:focus').parentElement.parentElement.nextElementSibling.querySelector('a.cue');
                // aNext.click();
                nextCue = this.cuesIndexed[this.lastActiveCueId + 1];
                this.jumpToTime(nextCue.startTime);


            }
            if (e.keyCode == 37) {

                //aPrev = document.querySelector('a:focus').parentElement.parentElement.previousElementSibling.querySelector("a.cue");
                //aPrev.click();
                prevCue = this.cuesIndexed[this.lastActiveCueId - 1];

                this.jumpToTime(prevCue.startTime);

            }
            if (e.keyCode == "R".charCodeAt(0)){

                var activeCue = this.cuesIndexed[this.lastActiveCueId];
                var currentTime =  this.video.currentTime;
                var cueDuration = activeCue.endTime - activeCue.startTime;
                var traceHold = cueDuration * 10/100;
                if ( cueDuration > 1 && (currentTime > activeCue.startTime) && (currentTime < activeCue.startTime + traceHold)) {
                    activeCue = this.cuesIndexed[this.lastActiveCueId - 1];
                }

                this.jumpToTime(activeCue.startTime);
                //aPrev = document.querySelector('a:focus')
                //aPrev.click();
                this.incModeState();

                if (this.video.paused )
                    this.video.play();
            }
            if (e.keyCode == "T".charCodeAt(0)){
                var activeCue = this.cuesIndexed[this.lastActiveCueId];
                this.jumpToTime(activeCue.startTime);

                //aPrev = document.querySelector('a:focus')
                //aPrev.click();
                this.resetModeState();

                if (this.video.paused )
                    this.video.play();
            }
            if (e.keyCode == 32 || e.keyCode == "P".charCodeAt(0)) {

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
        } else if (this.modeState == 3) {
            this.video.playbackRate = 0.5;
            this.track.mode = 'showing';
            this.track2.mode = 'showing';
        }
    }

    incModeState()
    {

        if (this.modeState < Video.modeStateMax)
            this.modeState++;
        else
            this.modeState  = 0;

        this.resolveModeState();
    }
    resetModeState()
    {

        this.modeState = 0;
        this.resolveModeState();

    }


    getVideo(){
        return document.getElementById(this.video_id);
    }


    init(){
        /**
         *
         * @var document Document
         * @var cue TextTrackCue
         */
        var  cue2, cue, timeStr, startTimeArr, td3, td2, td1, tr;

        this.track.mode = 'showing';
        this.track2.mode = 'showing';
        for (var i = 0; i <  this.track2.cues.length; i++) {
            cue = this.track2.cues[i];
            cue.line = 0;
            cue.id = i;
            //timeStr = '' + cue2.startTime + '';
            //startTimeArr = timeStr.split('.');
            //timeStr = Number.parseInt(startTimeArr[0]/60) + ':' + startTimeArr[0] % 60 ;
            //cues2Indexed[timeStr] = cue2;
        }

        for (var i = 0; i< this.track.cues.length; i++) {

            cue = this.track.cues[i];
            cue.id = i;
            this.cuesIndexed[i] = cue;


            //e.line = 10';
            //cue.align = 'right';
            //cue.size = "48";
            cue.line = -1;
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
            tr = $("<tr></tr>").append(td1).append(td2);


            tr.appendTo("#subtitles_container");


            //$(".cue").on("click", this.cueClickHandler);

            document.querySelectorAll('a.cue').forEach(cue=>cue.addEventListener('click', this.cueClickHandler));

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