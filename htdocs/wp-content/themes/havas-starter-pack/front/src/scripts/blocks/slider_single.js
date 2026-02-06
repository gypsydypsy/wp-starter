"use strict";

// core version + modules:
import Swiper, { Navigation, Scrollbar, Pagination, A11y } from "swiper";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/scrollbar";
import "swiper/css/a11y";
import "swiper/css/pagination";

const SliderSingle = {
  els: null,
  init: function () {
    SliderSingle.els = document.querySelectorAll(".f-slider.single--");
    if (SliderSingle.els && SliderSingle.els.length > 0) {
      SliderSingle.els.forEach((el) => {
        SliderSingle.create(el);
      });
    }
  },
  create: function (el) {
    const slider = el.querySelector(".swiper");
    const modules = [A11y, Navigation, Scrollbar, Pagination];
    const config = {
      modules,
      spaceBetween: 30,
      slidesPerView: 1,
      loop: false,
      speed: 600,
      autoHeight: true,
      on: {
        init() {
          const swiper = this;
          const paginationBullets =
            swiper.pagination.el?.querySelectorAll("[data-slide-index]");

          paginationBullets &&
            paginationBullets.forEach((bullet) => {
              const index = parseInt(
                bullet.getAttribute("data-slide-index"),
                10
              );

              bullet.addEventListener("click", () => {
                setTimeout(() => {
                  swiper.slideTo(index);
                }, 0);
              });

              bullet.addEventListener("keydown", (e) => {
                if (e.key === "Enter" || e.key === " ") {
                  e.preventDefault();
                  setTimeout(() => {
                    swiper.slideTo(index);
                  }, 0);
                }
              });
            });
        },
      },
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

      config.autoHeight = false;
    }

    // Textimage
    if (el.classList.contains("textImgSlider--")) {
      config.autoHeight = false;
    }

    // Pagination
    if (slider.dataset.pagination === "true") {
      const labelPrefix =
        slider
          .querySelector(".f-slider__pagination")
          ?.getAttribute("data-label") || "Go to slide";
      config.pagination = {
        el: el.querySelector(".f-slider__pagination"),
        clickable: true,
        type: "bullets",
        renderBullet: (index, className) => {
          return `<li><span class="${className}" role="button" data-slide-index="${index}"  aria-label="${labelPrefix} ${index + 1}"><span class="visually-hidden">${index + 1}</span></span></li>`;
        },
      };
    }

    new Swiper(slider, config);
  },
};

export default SliderSingle;
