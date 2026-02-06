'use strict';

function allowYoutubeAPI() {
    addYoutubeAPI();
    tarteaucitron.cookie.create('youtubeapi', true);
    tarteaucitron.userInterface.color('youtubeapi', true);
    tarteaucitron.state.youtubeapi = true;
}

function addYoutubeAPI() {
    var videoPlayers = document.querySelectorAll('.block__video .player-notallowed');

    for (var i = 0; i < videoPlayers.length; i++) {
        videoPlayers[i].classList.remove('player-notallowed');
        videoPlayers[i].innerHTML = '';
    }

    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

document.addEventListener('DOMContentLoaded', function (event) {
    var timer = setInterval(function () {
            if (!isEmpty(tarteaucitron.lang)) {

                if (tarteaucitron.state.youtubeapi == false || !tarteaucitron.state.youtubeapi || tarteaucitron.state.youtubeapi == 'wait') {

                    var youtubeHtml = '';
                    youtubeHtml += '<div class="tarteaucitron-yt-warning">';
                    youtubeHtml += '<p>';
                    youtubeHtml += tarteaucitron.lang.video.details;
                    youtubeHtml += '</p>';
                    youtubeHtml += '<p>';
                    youtubeHtml += tarteaucitron.lang.disclaimer;
                    youtubeHtml += '</p>';
                    youtubeHtml += '<button type="button" class="button" onclick="allowYoutubeAPI()">' + tarteaucitron.lang.allowbutton + '</div>';
                    youtubeHtml += '</div>';

                    var videoPlayers = document.querySelectorAll('.block__video .player-notallowed');

                    for (var i = 0; i < videoPlayers.length; i++) {
                        videoPlayers[i].innerHTML = youtubeHtml;
                    }
                } else {
                    addYoutubeAPI();
                }

                clearInterval(timer);
            }
        }
        ,
        250
    );

});

function isEmpty(obj) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key))
            return false;
    }
    return true;
}