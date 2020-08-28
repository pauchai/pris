
class Video {


    constructor(video_id){
        this.video_id = video_id;
        this.track = this.video[0].textTracks[0];
        this.track2 = this.video[0].textTracks[1];
        this.modeState = 1;

        this.cueEnter = (e) => {
            //var lineId = Number.parseFloat(this.startTime).toFixed(2)+Number.parseFloat(this.endTime).toFixed(2);
            //var regex = /\./gi
            //lineId = lineId.replace(regex,"");
            var lineId = e.currentTarget.id;
            $("#"+lineId).focus();
            $("#"+lineId).addClass("focused");

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

        // this.cueAClickHandler = e => {
        //     e.preventDefault();
        //     $(e.delegateTarget.querySelector('.cue')).click();
        //
        //     return false;
        // };

        this.handlerVideoPlayBackRate075 = (e) => {
          this.video[0].playbackRate = 0.75;
        };

        this.handlerVideoPlayBackRate1 = (e) => {
            this.video[0].playbackRate = 1;
        };


         this.handlerKeyDown = (e) => {
             var aNext, aPrev;
               if (e.keyCode == 17)
                     return;
                 if (e.keyCode == 39) {
                     aNext = $("a.focused").parent().parent().next().find("a");
                     aNext.click();

                 }
                 if (e.keyCode == 37) {
                     //$("a.focused").prev().prev().prev().click();
                     aPrev = $("a.focused").parent().parent().prev().find("a");
                     aPrev.click();

                 }
                 if (e.keyCode == "R".charCodeAt(0)){
                     aPrev = $("a.focused").trigger('click');

                     if (this.video[0].paused )
                         this.video[0].play();
                 }
                 if (e.keyCode == 32) {

                     if (this.video[0].paused || this.video[0].ended) {
                         this.video[0].play();
                     }
                     else {
                         this.video[0].pause();
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
        this.video[0].onloadeddata = (e) => {
            this.resolveModeState()
            this.init();

        };
    }





    resolveModeState()
    {

        if (this.modeState == 0){
            this.track.mode = 'hidden';
            this.track2.mode = 'hidden';
            this.video[0].playbackRate = 1;
        } else if (this.modeState == 1){
            this.video[0].playbackRate = 1;
           this.track.mode = 'showing';
           this.track2.mode = 'showing';
        }
    }

    /**
     *
     * @returns {jQuery|HTMLElement}
     */
    get video(){
        /**
         * @var document Document
         */
        return $("#" + this.video_id);
    }



    init(){
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
            cue.line = 100;
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


            $(".cue").on("click", this.cueClickHandler);

            $("a.cue").parent().parent().on("click", this.cueAClickHandler);
            //$("#subtitles_container").append($("<tr></tr>"))


            $("#video-speed-0_75").on("click", this.handlerVideoPlayBackRate075);
            $("#video-speed-1").on("click", this.handlerVideoPlayBackRate1);


            $(document).keydown(this.handlerKeyDown);

            $("#subtitles_container").on("mouseup", 'span', this.handlerSubtitlesContainerMouseUp);

        }

    }

    jumpToTime(time){
        this.video[0].currentTime = time;
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