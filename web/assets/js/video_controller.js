var open_overlay=false;
function controlVideo(video, startTime, endTime) {
    var overlay = $('#overlay-div');
    var captions = $('#captions-div');
    var play = $('.click-to-play');
    var pause = $('.click-to-pause');

    pause.css('display', 'block');
    play.css('display', 'none');

    var volume = $('.click-to-volume');

    startTime = timeToSeconds(startTime);
    endTime = timeToSeconds(endTime);

    // detect loading

    video.onwaiting = function(){
        showPlaceholder("loading-div");
    };
    video.onplaying = function(){
        hidePlaceholder("loading-div");
    };

//        video.stalled = function(){
//            alert('waiting');
//            showPlaceholder("loading-div");
//            overlay.css("display", "none");
//        };

    //play, pause, volume controls

    setInterval(function(){
        if (!isPlaying(video)) {
            play.css('display', 'block');
            pause.css('display', 'none');
            video.style.opacity = 0.5;
        }
    }, 1000);

    play.click(function () {
        if (!isPlaying(video)) {
            video.play();
            // title.beginElement();
            //desc.beginElement();
            setTimeout(startBuffer, 150);
            pause.css('display', 'block');
            play.css('display', 'none');
            video.style.opacity = 1;
            //overlay.css("display", "block");
        }
    });

    pause.click(function () {
        if (isPlaying(video)) {
            video.pause();
            play.css('display', 'block');
            pause.css('display', 'none');
            video.style.opacity = 0.5;
            //overlay.css("display", "none");
        }
    });
    volume.click(function () {
        $('.volume-bar').toggle('blind', {"direction": "down"});
        volumeControl(video);
    });

    //display current video play time
    //var startTime = 2;
    // var endTime = 6;
    //addTextTrack(video, startTime, endTime, title, desc);

    //display video buffering bar
    var startBuffer = function () {
        var currentBuffer = video.buffered.end(0);
        var maxduration = video.duration;
        var perc = 100 * currentBuffer / maxduration;
        $('.buffer-bar').css('width', perc + '%');

        if (currentBuffer < maxduration) {
            setTimeout(startBuffer, 500);
        }
    };

    var timeDrag = false;
    /* check for drag event */
    $('.progress').on('mousedown', function (e) {
        timeDrag = true;
        updatebar(e.pageX);
    });
    $(document).on('mouseup', function (e) {
        if (timeDrag) {
            timeDrag = false;
            updatebar(e.pageX);
        }
    });
    $(document).on('mousemove', function (e) {
        if (timeDrag) {
            updatebar(e.pageX);
        }
    });

    var updatebar = function (x) {
        var progress = $('.progress');

        //calculate drag position
        //and update video currenttime
        //as well as progress bar

        var maxduration = video.duration;
        var position = x - progress.offset().left;
        var percentage = 100 * position / progress.width();
        if (percentage > 100) {
            percentage = 100;
        }
        if (percentage < 0) {
            percentage = 0;
        }
        $('.time-bar').css('width', percentage + '%');
        video.currentTime = maxduration * percentage / 100;
        if(video.currentTime < endTime) {
            displayText(overlay, video, startTime, endTime);
        } else {
            if (overlay.hasClass('video-playing')) {
                overlay.removeClass('video-playing').animate({
                    width: '0',
                    padding: '0'
                });
                open_overlay=false;
            }else{
                overlay.css('width','0').css('padding','0');
                open_overlay=false;
            }
        }
    };

}

//functions
function isPlaying(video) {
    return (!video.paused && !video.ended);
}

function hasVolume(video) {
    return (video.volume !== 0);
}

var timeFormat = function (seconds) {
    var m = Math.floor(seconds / 60) < 10 ? "0" + Math.floor(seconds / 60) : Math.floor(seconds / 60);
    var s = Math.floor(seconds - (m * 60)) < 10 ? "0" + Math.floor(seconds - (m * 60)) : Math.floor(seconds - (m * 60));
    return m + ":" + s;
};

function showPlaceholder(div){
    $('.'+div).css('display', 'block');
}

function hidePlaceholder(div){
    $('.'+div).css('display', 'none');
}

function addTextTrack(video, startTime, endTime){
    var overlay = $('#overlay-div');

    startTime = timeToSeconds(startTime);
    endTime = timeToSeconds(endTime);

    video.ontimeupdate = function () {
        if(video.currentTime < endTime) {
            displayText(overlay, video, startTime, endTime);
        } else {
            if (overlay.hasClass('video-playing')) {
                overlay.removeClass('video-playing').animate({
                    width: '0',
                    padding: '0'
                });
                open_overlay=false;
            }else{
                overlay.css('width','0').css('padding','0');
                open_overlay=false;
            }
        }
        timeUpdate(video);
    };
}


function timeToSeconds(time){
    var temp = time.split(':');

    return parseInt(temp[2]) + parseInt(temp[1]* 60) + parseInt(temp[0] * 3600);
}

function timeUpdate(video){
    var currentPos = video.currentTime;
    var maxduration = video.duration;
    var perc = 100 * currentPos / maxduration;
    $('.time-bar').css('width', perc + '%');
    $('.current').html(timeFormat(currentPos));
}

function addSubtitles(video, file){

    var track = document.createElement("track");
    track.kind = "subtitles";
    track.label = "English";
    track.srclang = "en";
    track.src = file;
    track.default = true;
    track.addEventListener("load", function() {
        track.mode = "showing";
        video.textTracks[0].mode = "showing"; // thanks Firefox
    });

    video.appendChild(track);

    //alert(video.innerHTML);
}

function displayControlbar() {
    var vid = document.getElementById('myVideo');
    vid.style.opacity = 0.5;
    $('.controls-bar').css('display', 'block');
    if($(window).width() > 550) {
        $('.menu-playlist-div').css('display', 'block');
        $('#back-button-web').css('display', 'block');
    }
}

function hideControlbar(){
    var vid = document.getElementById('myVideo');
    vid.style.opacity = 1;
    $('.controls-bar').css('display', 'none');
    if($(window).width() > 550) {
        $('.menu-playlist-div').css('display', 'none');
        $('#back-button-web').css('display', 'none');
    }
}

function hideBar(video){
    video.style.opacity = 1;
    $('.controls-bar').css('display', 'none');
    if($(window).width() > 550) {
        $('.menu-playlist-div').css('display', 'none');
        $('#back-button-web').css('display', 'none');
    }

    hidePlaylistAndSidebar();

}

function hidePlaylistAndSidebar(){

    var menu = $('.menu-icon-hover');
    var playlist = $('.playlist-icon-hover');
    var sidebar = $('.sidebar-tv');
    var playlistbar = $('.playlist-tv');
    var menu_playlist = $('.menu-playlist-div');
    var wrapper =  $('.wrapper');
    var controls = $('.controls-bar');
    var controls_div = $('.controls-div');
    var progress_bar = $('.progress-bar');
    var back = $('.menu-back-div');

    playlistbar.animate({
        left: "0"
    }).css('width', '100%');

    if(playlistbar.hasClass('toggle-false') && sidebar.hasClass('toggle-false')){
        playlistbar.toggle('blind', {"direction": "down"}).removeClass('toggle-false').addClass('toggle-true');
        sidebar.toggle('slide').removeClass('toggle-false').addClass('toggle-true');
    }

    else if(playlistbar.hasClass('toggle-false')) {
        playlistbar.toggle('blind', {"direction": "down"}).removeClass('toggle-false').addClass('toggle-true');

    }

    else if(sidebar.hasClass('toggle-false')) {
        sidebar.toggle('slide').removeClass('toggle-false').addClass('toggle-true');


    }

    menu_playlist.animate({
        left: "0"
    });
    if(back.css('left') != 'auto') {
        back.animate({
            left: "-6px"
        });
    }
    wrapper.animate({
        left: "0"
    });
    playlistbar.animate({
        left: "0"
    }).css('width', '100%');
    progress_bar.css('width', '88%');
    controls.animate({
        left: "-15px"
    }).css('width', '100%');
    controls_div.animate({
        left: "0"
    });

}

function displayBar(){
    $('.controls-bar').css('display', 'block');
    if($(window).width() > 550) {
        $('.menu-playlist-div').css('display', 'block');
        $('#back-button-web').css('display', 'block');
    }
}

function displayText(overlay, video, startTime, endTime){
    if( !overlay.hasClass('video-playing')) {
        if (video.currentTime > startTime) {
            overlay.addClass('video-playing');
            open_overlay = true;
            overlayDivCSS();
        }
    }else{
        if (video.currentTime < startTime) {
            overlay.removeClass('video-playing').animate({
                width: '0',
                padding: '0'
            });
            open_overlay=false;
        }
    }
}


function volumeControl(video){
    //VOLUME BAR
    //volume bar event
    var volumeDrag = false;
    var volumeBar = $('.volume-bar');
    $('.volume-bar').on('mousedown', function(e) {
        volumeDrag = true;
        video[0].muted = false;
        updateVolume(e.pageY);
    });
    $(document).on('mouseup', function(e) {
        if(volumeDrag) {
            volumeDrag = false;
            updateVolume(e.pageY);
        }
    });
    $(document).on('mousemove', function(e) {
        if(volumeDrag) {
            updateVolume(e.pageY);
        }
    });
    var updateVolume = function(y, vol) {
        var volume = $('.volume-bar');
        var percentage;

        //if only volume have specificed
        //then direct update volume
        if(vol) {
            percentage = vol * 100;
        }
        else {
            var position = y - volume.offset().top;
            var pos = volumeBar.height() - position;
            percentage = 100 * pos / volume.height();
        }

        if(percentage > 100) {
            percentage = 100;
        }
        if(percentage < 0) {
            percentage = 0;
        }

        //update volume bar and video volume
        $('.volume-level').css('height',percentage+'%');
        video.volume = percentage / 100;

        if (video.volume > 0) {
            $('.toggleVolume').removeClass('fa-volume-off').addClass('fa-volume-up');
        } else if (video.volume == 0) {
            $('.toggleVolume').removeClass('fa-volume-up').addClass('fa-volume-off');
            video.volume = 0;
        }

    };

}

$(window).resize(function(){
        overlayDivCSSResize();
});
function overlayDivCSSResize(){
    var overlay_div = $("#overlay-div");
    if($(window).width() < 550){
        overlay_div.css("height", "70px");
        if(open_overlay==true) {
            overlay_div.css('width', '80%');
            $("#overlay-title").css("font-size", "1em");
            $("#overlay-content").css("font-size", "1em");
        }

    }else if($(window).width() < 750){
        overlay_div.css("height", "100px");
        if(open_overlay==true) {
            overlay_div.css('width', '70%');
            $("#overlay-title").css("font-size", "2em");
            $("#overlay-content").css("font-size", "1.5em");
        }
    }else if($(window).width() < 950){
        overlay_div.css("height", "120px");
        if(open_overlay==true) {
            overlay_div.css('width', '60%');
            $("#overlay-title").css("font-size", "2.2em");
            $("#overlay-content").css("font-size", "1.7em");
        }
    }else{
        overlay_div.css("height", "150px");
        if(open_overlay==true) {
            overlay_div.css('width', '50%');
            overlay_div.css('padding', '20px');
            $("#overlay-title").css("font-size", "2.8em");
            $("#overlay-content").css("font-size", "1.5em");
        }
    }

}

function overlayDivCSS(){
    var overlay_div = $("#overlay-div");
    if($(window).width() < 550){
        overlay_div.css("height", "70px");
        if(open_overlay==true) {
            overlay_div.css('width', '80%');
            $("#overlay-title").css("font-size", "1em");
            $("#overlay-content").css("font-size", "1em");
        }

    }else if($(window).width() < 750){
        overlay_div.css("height", "100px");
        if(open_overlay==true) {
            overlay_div.animate({
                width: "70%"
            });
            $("#overlay-title").css("font-size", "2em");
            $("#overlay-content").css("font-size", "1.5em");
        }
    }else if($(window).width() < 950){
        overlay_div.css("height", "120px");
        if(open_overlay==true) {
            overlay_div.animate({
                width: "60%"
            });
            $("#overlay-title").css("font-size", "2.2em");
            $("#overlay-content").css("font-size", "1.7em");
        }
    }else{
        overlay_div.css("height", "150px");
        if(open_overlay==true) {
            overlay_div.animate({
                width: '50%',
                padding: '20px'
            });
            $("#overlay-title").css("font-size", "2.8em");
            $("#overlay-content").css("font-size", "1.5em");
        }
    }

}

