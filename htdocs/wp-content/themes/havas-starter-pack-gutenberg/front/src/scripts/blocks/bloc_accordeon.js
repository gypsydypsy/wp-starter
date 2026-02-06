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
                        toggleContent.classList.toggle('active');
                    }
                });
            });
        }
    },
};

export default Accordion;