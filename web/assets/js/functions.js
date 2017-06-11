//header.php
function toggleSidebar() {
        var menu = $('.menu-icon-hover');
        var playlist = $('.playlist-icon-hover');
        var sidebar = $('.sidebar-tv');
        var playlistbar = $('.playlist-tv');
        var menu_playlist = $('.menu-playlist-div');
        var wrapper =  $('.wrapper');
        var controls = $('.controls-bar');
        var progress_bar = $('.progress-bar');
        var back = $('.menu-back-div');

        if (sidebar.hasClass('toggle-false')) {
            sidebar.toggle('slide');
            sidebar.removeClass('toggle-false').addClass('toggle-true');
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
        } else if (sidebar.hasClass('toggle-true')) {
            sidebar.toggle('slide');
            sidebar.removeClass('toggle-true').addClass('toggle-false');
            menu_playlist.animate({
                left: "270px"
            });
            wrapper.animate({
                left: "270px"
            });
            if(back.css('left') != 'auto'){
                back.animate({
                    left: "266px"
                });
            }
            playlistbar.animate({
                left: "300px"
            }).css('width', '86%');
            progress_bar.css('width', '86%');
            controls.animate({
                left: "270px"
            }).css('width', '85%');

        }
}

function togglePlaylist() {
    var menu = $('.menu-icon-hover');
    var playlist = $('.playlist-icon-hover');
    var sidebar = $('.sidebar-tv');
    var playlistbar = $('.playlist-tv');
    var wrapper =  $('.wrapper');
    var controls = $('.controls-bar');
    var back = $('.menu-back-div');

    if (playlistbar.hasClass('toggle-false')) {
            playlistbar.removeClass('toggle-false').addClass('toggle-true');
            playlistbar.toggle('blind',{"direction": "down"});
        } else if (playlistbar.hasClass('toggle-true')) {
            playlistbar.removeClass('toggle-true').addClass('toggle-false');
            playlistbar.toggle('blind',{"direction": "down"});
        }
}

function itemHover(index){
    var this_video = $("#myVideo"+index);
    if(!this_video.hasClass('playing')){
        this_video.addClass('playing');
        this_video[0].play();
        this_video[0].volume = 0;
        this_video[0].style.opacity = 1;
        $('.video-actions-' + index).animate({
            height: '100%'
        }).css('display', 'block');
    }
}

function itemNotHover(index){
    var this_video = $("#myVideo"+index);
    if(this_video.hasClass('playing')) {
        this_video.removeClass('playing');
        $('.videos-action').css('height', '0').css('display', 'none');
        this_video[0].pause();
        this_video[0].load();
        this_video[0].volume = 0;
        this_video[0].style.opacity = 0.5;
    }
}
