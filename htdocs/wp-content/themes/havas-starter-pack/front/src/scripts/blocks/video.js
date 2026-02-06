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
                if(poster.dataset.typeVideo === "youtube" && tarteaucitron && tarteaucitron.state.youtube) {
                    let src = el.querySelector('iframe').getAttribute('src').replace('autoplay=0', '').replace('muted=0', '').replace('playsinline=0', '')
                    if(src.includes('?')){
                        src+="&autoplay=1&playsinline=1"
                    }
                    else {
                        src+="?autoplay=1&playsinline=1"
                    }
                    el.querySelector('.youtube_player').innerHTML = `
                        <iframe frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; playsinline; encrypted-media; gyroscope; picture-in-picture" title="YouTube video player" width="100%" height="100%" src="${src}"></iframe>`
                }
                else if(poster.dataset.typeVideo === "vimeo" && tarteaucitron && tarteaucitron.state.vimeo) {
                    let src = el.querySelector('iframe').getAttribute('src').replace('autoplay=0', '').replace('muted=0', '').replace('playsinline=0', '');
                    if(src.includes('?')){
                        src+="&autoplay=1&autopause=0"
                    }
                    else {
                        src+="?autoplay=1&autopause=0"
                    }
                    el.querySelector('.vimeo_player').innerHTML = `
                        <iframe width="100%" height="100%" frameborder="0" webkitallowfullscreen="" mozallowfullscreen=""  allow="autoplay" allowfullscreen="" src="${src}"></iframe>`
                }
                else if(poster.dataset.typeVideo === "dailymotion" && tarteaucitron && tarteaucitron.state.dailymotion) {
                    let src = el.querySelector('iframe').getAttribute('src').replace('autoplay=0', '').replace('muted=0', '').replace('playsinline=0', '');
                    if(src.includes('?')){
                        src+="&autoplay=1&autopause=0"
                    }
                    else {
                        src+="?autoplay=1&autopause=0"
                    }
                    el.querySelector('.dailymotion_player').innerHTML = `
                        <iframe width="100%" height="100%" frameborder="0" webkitallowfullscreen="" mozallowfullscreen=""  allow="autoplay" allowfullscreen="" src="${src}"></iframe>`
               }
               else if(poster.dataset.typeVideo === "mp4") {

               }
            });
        }
    }
};

export default Video;