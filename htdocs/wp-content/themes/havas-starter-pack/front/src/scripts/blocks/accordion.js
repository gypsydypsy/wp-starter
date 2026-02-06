'use strict';

const Accordion = {
    init: function () {
        const toggleButtons = document.querySelectorAll('.toggle-button');
        if (toggleButtons && toggleButtons.length > 0) {
            toggleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const toggleContent = button.parentElement.querySelector('.toggle-content');
                    if (toggleContent) {
                        button.classList.toggle('active');
                        button.setAttribute('aria-expanded', button.classList.contains('active'));
                        toggleContent.classList.toggle('active');
                    }
                });
            });
        }

        const loadMoreButtons = document.querySelectorAll('.f-accordion__list-loadmore.active button');
        if(loadMoreButtons && loadMoreButtons.length >0){
            loadMoreButtons.forEach(button => {
                const container = button.closest('.f-accordion');
                const hiddenEls = container.querySelectorAll('.f-accordion__list-item.hidden');
                button.addEventListener('click', () => {
                   button.parentElement.classList.remove('active');
                   hiddenEls.forEach(el => {
                      el.classList.remove('hidden');
                   });
                });
            });
        }
    },
};

export default Accordion;