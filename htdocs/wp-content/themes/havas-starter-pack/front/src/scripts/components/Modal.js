"use strict";

import { getFocusableElts } from "../utils/accessibility";

const Modal = {
  triggers: [],
  init: function () {
    Modal.triggers = document.querySelectorAll(".js-open-modal");

    if (Modal.triggers.length) {
      Modal.triggers.forEach((trigger) => {
        const id = trigger.getAttribute("aria-controls");
        const modal = document.getElementById(id);
        const closeBtn = document.querySelector(
          `.js-close-modal[aria-controls="${id}"]`
        );

        if (modal && closeBtn) {
          Modal.initOne(trigger, modal, closeBtn);
        }
      });
    }
  },
  initOne: function (trigger, modal, closeBtn) {
   
    const focusElts = getFocusableElts(modal);
    const firstElement = focusElts[0];
    const lastElement = focusElts[focusElts.length - 1];

    /* Handle keyboard nav */
    function trapFocus(e) {
      const isTabPressed = e.key === "Tab" || e.keyCode === 9;
      const isEscPressed = e.key === "Escape" || e.keyCode === 27;

      if (isEscPressed) closeBtn.click();

      if (isTabPressed) {

        if (e.shiftKey && document.activeElement === firstElement) {
          e.preventDefault();
          lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
          e.preventDefault();
          firstElement.focus();
        }
      }
    }

    /* Handle Open */
    trigger.addEventListener("click", (e) => {
      modal.classList.add("open");
      window.addEventListener("keydown", trapFocus);

      const isKeyboardClick =
        e.pointerType !== "click" && e.pointerType !== "touch";

      if (firstElement && isKeyboardClick) {
        firstElement.focus();
      }
    });

    /* Handle Close */
    closeBtn.addEventListener("click", (e) => {
      modal.classList.remove("open");
      window.removeEventListener("keydown", trapFocus);

      const isKeyboardClick =
        e.pointerType !== "click" && e.pointerType !== "touch";

      if (isKeyboardClick) trigger.focus();
    });

    /* Handle click outside */
    const overlay = modal.querySelector('.c-modal__overlay')
    if(overlay){
      overlay.addEventListener('click', () => closeBtn.click())
    }
  },
};

export default Modal;
