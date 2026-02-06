'use strict';

const Video = {
    els: null,
    init: function () {
        Video.els = document.querySelectorAll('.f-video');
        if (Video.els && Video.els.length > 0) {
            Video.els.forEach(el => {
                Video.managePoster(el);
            });
        }
    },
    managePoster: function (el) {
        const poster = el.querySelector('.c-video__player-poster');
        if(poster){
        const buttonPlay = poster.querySelector('.c-video__player-poster-play');
            buttonPlay.addEventListener('click', () => {
               poster.classList.add('hidden');
            });
        }
    }
};

export default Video;