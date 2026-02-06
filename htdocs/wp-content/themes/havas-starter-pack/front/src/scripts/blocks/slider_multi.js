"use strict";

// core version + modules:
import Swiper, { Navigation, Scrollbar, A11y } from "swiper";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/scrollbar";
import "swiper/css/a11y";
import "swiper/css/pagination";

const SliderMulti = {
  els: null,
  init: function () {
    SliderMulti.els = document.querySelectorAll(".f-slider.multi--");
    if (SliderMulti.els && SliderMulti.els.length > 0) {
      SliderMulti.els.forEach((el) => {
        SliderMulti.create(el);
      });
    }
  },
  create: function (el) {
    const slider = el.querySelector(".swiper");
    const modules = [A11y, Navigation, Scrollbar];
    const config = {
      modules,
      spaceBetween: 40,
      slidesPerView: "auto",
      loop: false,
      speed: 600,
      autoHeight: false,
    };

    // Navigation type : arrows
    if (slider.dataset.navigation === "arrows") {
      config.navigation = {
        prevEl: ".f-slider__navigation-prev",
        nextEl: ".f-slider__navigation-next",
      };
      config.a11y = {
        prevSlideMessage:
          slider
            .querySelector(".f-slider__navigation-prev")
            ?.getAttribute("data-label") || "previous slide",
        nextSlideMessage:
          slider
            .querySelector(".f-slider__navigation-next")
            ?.getAttribute("data-label") || "next slide",
      };
    }

    // Navigation type : scrollbar
    if (slider.dataset.navigation === "scrollbar") {
      config.scrollbar = {
        el: ".f-slider__scrollbar",
        hide: false,
        draggable: true,
      };
    }

    new Swiper(slider, config);

  },
};

export default SliderMulti;
