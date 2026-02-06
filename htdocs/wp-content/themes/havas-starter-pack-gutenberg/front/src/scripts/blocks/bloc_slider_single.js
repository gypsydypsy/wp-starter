'use strict';

// core version + modules:
import Swiper, {Navigation, Scrollbar} from 'swiper';

// Import Swiper styles
import "swiper/swiper.min.css";
import "swiper/modules/navigation/navigation.min.css";
import "swiper/modules/scrollbar/scrollbar.min.css";


const SliderSingle = {
    els: null,
    init: function () {
        SliderSingle.els = document.querySelectorAll('.f-slider.single--');
        if (SliderSingle.els && SliderSingle.els.length > 0) {
            SliderSingle.els.forEach(el => {
                SliderSingle.create(el);
            });
        }
    },
    create: function (el) {
        const slider = el.querySelector('.swiper');

        let swiper = null;


        let modules = "";
        let navigation = null;
        let scrollbar = null;

        if (slider.dataset.navigation === "fleches") {
            swiper = new Swiper(slider, {
                modules: [Navigation],
                spaceBetween: 0,
                slidesPerView: 1,
                loop: false,
                speed: 600,
                autoHeight:true,
                navigation: {
                    prevEl: ".f-slider__navigation-prev",
                    nextEl: ".f-slider__navigation-next",
                }
            });
        }
        if (slider.dataset.navigation === "scrollbar") {
            swiper = new Swiper(slider, {
                modules: [Scrollbar],
                spaceBetween: 0,
                slidesPerView: 1,
                loop: false,
                speed: 600,
                autoHeight:false,
                scrollbar: {
                    el: ".f-slider__scrollbar",
                    hide: false,
                    draggable: true
                }
            });
        }

    },
};

export default SliderSingle;