export function getFocusableElts(parent) {

    const elts = Array.from(
    parent.querySelectorAll(
      'button:not(.hide):not([disabled]), a[href]:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"]), iframe, .focus-guard'
    )
  )

  return elts;
}

'use strict'

const Accessibility = {
    _keydownHandler: null,

    init: function () {
        // RGPD modal
        const timer = setInterval(() => {
            const tarteaucitronAlertBig = document.querySelector('#tarteaucitronAlertBig');
            const tarteaucitronAlertBigContent = document.querySelector('#tarteaucitronAlertBigContainer  ');

            if (tarteaucitronAlertBig && tarteaucitronAlertBigContent) {
                const displayStyle = window.getComputedStyle(tarteaucitronAlertBig).display;
                if (displayStyle === 'block') {
                    Accessibility.focusFirstElement(tarteaucitronAlertBigContent, false);

                    this._keydownHandler = (e) => {
                        Accessibility.trapFocus(tarteaucitronAlertBig, e, null);
                    };

                    document.addEventListener("keydown", this._keydownHandler);
                    clearInterval(timer);
                }
            }
        }, 500);

       /* document.addEventListener("keydown", () => console.log('focusEl', document.activeElement));*/
    },
    destroy: function() {
        if (this._keydownHandler) {
            document.removeEventListener("keydown", this._keydownHandler);
            this._keydownHandler = null;
        }
    },
    focusFirstElement: function (el, skip = true) {
        const elements = el.querySelectorAll('a, button');
        if (elements) {
            if (skip) {
                if (elements[1]) {
                    elements[1].focus();
                } else {
                    elements[0].focus();
                }
            }
            elements[0].focus();
        }
    },
    trapFocus: function (popin, e, closeFunction) {
        const focusableElements = Array.from(popin.querySelectorAll(
            'button:not(.hide):not([disabled]), a[href]:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"]), iframe, .focus-guard'
        )).filter(el => {
            // Additional check for visibility and display
            const style = window.getComputedStyle(el);
            return style.display !== 'none' && style.visibility !== 'hidden' && style.opacity !== '0';
        });

        let isTabPressed = e.key === "Tab" || e.keyCode === 9;
        let isEscPressed = e.key === "Escape" || e.keyCode === 27;

        if (isTabPressed) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }

        if (isEscPressed) {
            if (closeFunction) {
                closeFunction();
            }
        }
    }
}

export default Accessibility;