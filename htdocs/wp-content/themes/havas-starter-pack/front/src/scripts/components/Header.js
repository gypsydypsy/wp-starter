"use strict";

import { getFocusableElts } from "../utils/accessibility";

const Header = {
  el: null,
  toggle: null,
  nav: null,
  init: function () {
    Header.el = document.querySelector(".header");
    if (Header.el) {
      Header.toggle = Header.el.querySelector(".header__toggle");
      Header.nav = Header.el.querySelector(".header__nav");

      if (Header.toggle) {
        Header.toggle.addEventListener("click", (e) => {
          Header.el.classList.toggle("navOpened");

          const menuIsOpened = Header.el.classList.contains("navOpened");
          const isKeyboardClick =
            e.pointerType !== "click" && e.pointerType !== "touch";

          Header.toggle.setAttribute("aria-expanded", menuIsOpened);

          if (menuIsOpened) {
            let focusElts = getFocusableElts(Header.nav);
            if (isKeyboardClick && focusElts.length) {
              focusElts[0].focus();
            }
            window.addEventListener("keyup", Header.handleKeyboardNavigation);
          } else {
            window.removeEventListener("keyup", Header.handleKeyboardNavigation);
          }
        });
      }
    }
  },
  handleKeyboardNavigation: function (e) {
    let isEscPressed = e.key === "Escape" || e.keyCode === 27
    let focusElts = getFocusableElts(Header.nav);

    if(!focusElts.includes(document.activeElement) || isEscPressed){
        Header.toggle.click()
    }
  },
};

export default Header;
