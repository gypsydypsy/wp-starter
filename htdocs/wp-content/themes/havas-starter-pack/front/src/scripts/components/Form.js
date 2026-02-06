'use strict';

const axios = require('axios');
import {slideUp, slideDown} from "../utils/slide-toggle";

const Form = {
    el: null,
    recaptcha_response: "",
    init: function () {

        Form.el = document.querySelector('.c-form');
        if (Form.el) {
            Form.manageForm(Form.el.querySelector('form'));
            window.verifyCaptcha = function verifyCaptcha(token) {
                Form.recaptcha_response = token;
            };

        }
    },
    manageForm: function (form) {
        if (form) {

            let selectObject = form.querySelector('[name="object"]').value;
            const conditions = form.querySelectorAll('[data-condition]');
            if(conditions.length >0){
                conditions.forEach(condition => {
                    if (condition.dataset.condition !== selectObject) {
                        slideUp(condition, 0);
                    } else {
                        setTimeout(() => {
                            slideDown(condition, 400);
                        }, 300);
                    }
                });
                Form.handlePurposeOfContact(form, selectObject);

                form.querySelector('[name="object"]').addEventListener('change', (e) => {
                    selectObject = e.target.value;
                    Form.handlePurposeOfContact(form, selectObject);
                    const conditions = form.querySelectorAll('[data-condition]');
                    conditions.forEach(condition => {
                        if (condition.dataset.condition !== selectObject) {
                            slideUp(condition, 0);
                        } else {
                            setTimeout(() => {
                                slideDown(condition, 400);
                            }, 300);
                        }
                    });
                });
            }


            const datesPicker = form.querySelectorAll('[type="date"]');
            if (datesPicker) {
                datesPicker.forEach(datepicker => {
                    datepicker.addEventListener('change', (e) => {
                        const placeholder = datepicker.closest('.dateCtn__item-input').querySelector('.placeholder');
                        placeholder.classList.add('active');
                        const datepickerValueArray = datepicker.value.split('-');
                        const year = datepickerValueArray[0];
                        const month = datepickerValueArray[1];
                        const day = datepickerValueArray[2];
                        placeholder.innerText = day + "/" + month + "/" + year;
                    });
                });
            }


            let compareInputs = false;
            // let recaptchaWidget;
            // setTimeout(function () {
            //     recaptchaWidget = grecaptcha.render(form.querySelector('.g-recaptcha'));
            // }, 300);

            let inputs = form.querySelectorAll('.form-group input, .form-group textarea, .form-group select');

            for (let i = 0; i < inputs.length; i++) {
                const elementError = inputs[i].closest('.form-group').querySelector('.error');

                let eventList = ["change", "keyup", "paste", "input", "propertychange"];
                for (event of eventList) {
                    inputs[i].addEventListener(event, function () {

                        if (form.querySelector('.error.global--').classList.contains('active')) {
                            form.querySelector('.error.global--').classList.remove('active');
                        }

                        // Each time the user types something, we check if the
                        // form fields are valid.

                        if (inputs[i].dataset.compare) {
                            if (inputs[i].value !== "") {
                                if (compareInputs === false) {
                                    compareInputs = true;
                                }
                            }
                        }


                        if (!inputs[i].validity.valid) {
                            // If there is still an error, show the correct error
                            Form.showError(form, inputs[i], elementError);
                            // console.log(inputs[i].validity);
                        } else {
                            // In case there is an error message visible, if the field
                            // is valid, we remove the error message.
                            elementError.textContent = ''; // Reset the content of the message
                            inputs[i].closest('.form-group').classList.remove('error'); // Reset the visual state of the message
                            if (inputs[i].dataset.compare) {
                                if (compareInputs === false) {
                                    // If there is still an error, show the correct error
                                    Form.showError(form, inputs[i], elementError);
                                } else {
                                    let otherInput = form.querySelectorAll('[data-compare="true"]');
                                    otherInput.forEach(input => {
                                        const elementError = input.closest('.form-group').querySelector('.error');
                                        elementError.textContent = ''; // Reset the content of the message
                                        input.closest('.form-group').classList.remove('error'); // Reset the visual state of the message
                                    });
                                }

                            }
                        }
                    });
                }
            }


            form.querySelector('.js-submit').addEventListener('click', function (e) {

                let validArr = [];
                for (let i = 0; i < inputs.length; i++) {
                    // console.log(inputs[i]);
                    // console.log(inputs[i].closest('.form-group'));
                    if (!inputs[i].classList.contains('g-recaptcha-response')) {
                        const elementError = inputs[i].closest('.form-group').querySelector('.error');
                        if (!inputs[i].validity.valid) {
                            //console.log('not valid field', inputs[i]);
                            // If it isn't, we display an appropriate error message
                            Form.showError(form, inputs[i], elementError);
                            validArr.push(false);
                        } else {
                            //console.log('valid field', inputs[i]);
                            if (inputs[i].dataset.compare) {
                                if (compareInputs === false) {
                                    Form.showError(form, inputs[i], elementError);
                                    validArr.push(false);
                                } else {
                                    validArr.push(true);
                                }
                            } else {
                                validArr.push(true);
                            }
                        }
                    }
                }


                if (Form.recaptcha_response.length === 0) {
                    form.querySelector('.captcha--').classList.add('active');
                } else {
                    if (form.querySelector('.captcha--').classList.contains('active')) {
                        form.querySelector('.captcha--').classList.remove('active');
                    }
                }

                if (validArr.includes(false) || Form.recaptcha_response.length === 0) {
                    // Then we prevent the form from being sent by canceling the event
                    form.querySelector('.global--').classList.add('active');
                    // e.preventDefault();
                } else {

                    form.querySelector('.global--').classList.remove('active');

                    let url = apiData.send_contact;
                    if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
                        url = "http://localhost:3000/wp-json/xxx/v1/send-contact/?lang=fr";
                    }
                    if (url) {

                        form.querySelector('.js-submit').classList.add('loading');

                        form.querySelector('.global-- span').textContent = '';

                        let formData = new FormData();

                        const firstname = form.querySelector('[name="firstname"]');
                        if (firstname && firstname.value) {
                            formData.append('firstname', firstname.value);
                        }

                        const lastname = form.querySelector('[name="lastname"]');
                        if (lastname && lastname.value) {
                            formData.append('lastname', lastname.value);
                        }

                        const email = form.querySelector('[name="email"]');
                        if (email && email.value) {
                            formData.append('email', email.value);
                        }

                        const phone = form.querySelector('[name="phone"]');
                        if (phone && phone.value) {
                            formData.append('phone', phone.value);
                        }

                        formData.append('subject', selectObject);

                        // all subject
                        const message = form.querySelector('[name="message"]');
                        if (message && message.value) {
                            formData.append('message', message.value);
                        }

                        const optin = form.querySelector('[name="optin"]');
                        if (optin) {
                            formData.append('optin_rgpd', optin.value === 'on' ? 1 : 0);
                        }

                        formData.append('g-recaptcha-response', Form.recaptcha_response);


                        axios({
                            method: "post",
                            url: url,
                            data: formData,
                            headers: {"Content-Type": "multipart/form-data"},
                        }).then(function (response) {
                                //handle success
                                //  console.log('success', response);
                                Form.recaptcha_response = "";
                                form.querySelector('.js-submit').classList.remove('loading');
                                if (response.data.code === 'all_good') {
                                    form.querySelector('.success').classList.add('active');
                                    form.querySelector('.success').innerHTML = response.data.data.msg;
                                    setTimeout(() => {
                                        form.querySelector('.success').classList.remove('active');
                                    }, 5000);
                                } else if (response.data.code === 'error_validation') {
                                    form.querySelector('.global--').classList.add('active');
                                    form.querySelector('.global-- span').textContent = response.data.data.msg;
                                    if (response.data.data.errors) {
                                        let arr = response.data.data.errors;
                                        if (!Array.isArray(response.data.data.errors)) {
                                            arr = Object.entries(response.data.data.errors).map((e) => ({[e[0]]: e[1]}));
                                        }
                                        arr.forEach(error => {
                                            if (error.firstname) {
                                                const formGroup = firstname.closest('.form-group');
                                                formGroup.classList.add('error');
                                                formGroup.querySelector('.error').innerHTML = error.firstname;
                                            }
                                            if (error.lastname) {
                                                const formGroup = lastname.closest('.form-group');
                                                formGroup.classList.add('error');
                                                formGroup.querySelector('.error').innerHTML = error.lastname;
                                            }
                                            if (error.email) {
                                                const formGroup = email.closest('.form-group');
                                                formGroup.classList.add('error');
                                                formGroup.querySelector('.error').innerHTML = error.email;
                                            }
                                            if (error.phone) {
                                                const formGroup = phone.closest('.form-group');
                                                formGroup.classList.add('error');
                                                formGroup.querySelector('.error').innerHTML = error.phone;
                                            }
                                            if (error.subject) {
                                                const formGroup = object.closest('.form-group');
                                                formGroup.classList.add('error');
                                                formGroup.querySelector('.error').innerHTML = error.subject;
                                            }
                                            if (error.optin_rgpd) {
                                                const formGroup = optin.closest('.form-group');
                                                formGroup.classList.add('error');
                                                formGroup.querySelector('.error').innerHTML = error.optin_rgpd;
                                            }
                                            if (error['g-recaptcha-response']) {
                                                const formGroup = form.querySelector('.g-recaptcha').closest('.form-group');
                                                formGroup.classList.add('error');
                                                formGroup.querySelector('.error.captcha--').innerHTML = error['g-recaptcha-response'];
                                            }

                                        });

                                        console.log('errors', arr);

                                    }
                                }
                                else if (response.data.code === 'error_db') {
                                    form.querySelector('.global--').classList.add('active');
                                    form.querySelector('.global-- span').textContent = response.data.data.errors.global;
                                }
                                form.reset();
                                grecaptcha.reset();
                                // grecaptcha.reset(recaptchaWidget);
                            }
                        ).catch(function (response) {
                            //handle error
                            console.log('err', response);
                            Form.recaptcha_response = "";

                            form.querySelector('.js-submit').classList.remove('loading');
                            form.querySelector('.global--').classList.add('active');
                            form.reset();
                            grecaptcha.reset();
                            // grecaptcha.reset(recaptchaWidget);
                        });
                    }
                }

            });
        }
    },
    showError: function (form, element, elementError) {
        if (element.validity.valueMissing) {
            // If the field is empty,
            // display the following error message.
            elementError.textContent = form.querySelector('.error-message-required').textContent;
        } else if (element.validity.typeMismatch) {
            // If the field doesn't contain an email address,
            // display the following error message.
            if (element.dataset.alphabet) {
                elementError.textContent = form.querySelector('.error-message-invalid-alphabet').textContent;
            }
            else if (element.dataset.email) {
                elementError.textContent = form.querySelector('.error-message-invalid-email').textContent;
            }
            else if (element.dataset.phone) {
                elementError.textContent = form.querySelector('.error-message-invalid-phone').textContent;
            } else {
                elementError.textContent = form.querySelector('.error-message-invalid').textContent;
            }
        } else if (element.validity.tooLong) {
            // If the data is too long,
            // display the following error message.
            elementError.textContent = form.querySelector('.error-message-toolong').textContent.replace('[MAXLENGTH]', element.dataset.maxlength);
        }
        else if (element.validity.tooShort) {
            // If the data is too short,
            // display the following error message.
            elementError.textContemnt = form.querySelector('.error-message-tooshort').textContent;
        }
        else if (element.validity.patternMismatch) {
            elementError.textContent = form.querySelector('.error-message-invalid').textContent;
        }
        else if (element.dataset.compare) {
            elementError.textContent = form.querySelector('.error-message-compare').textContent;
        }

        // Set the styling appropriately
        element.closest('.form-group').classList.add('error');
    },
    handlePurposeOfContact: function (form, selectObject) {
        if (selectObject) {
            let hiddenFields = form.querySelectorAll('[data-condition]');
            if (hiddenFields) {
                for (let h = 0; h < hiddenFields.length; h++) {
                    const selectFields = hiddenFields[h].querySelectorAll('select');
                    const inputFields = hiddenFields[h].querySelectorAll('input');
                    const textareaFields = hiddenFields[h].querySelectorAll('textarea');

                    const setRequired = (el, selectObject, hiddenFields) => {
                        if (selectObject === hiddenFields.dataset.condition) {
                            if (el.dataset.required) {
                                el.required = true;
                            }
                        }
                        else {
                            el.required = false;
                        }
                    };

                    if (selectFields && selectFields.length > 0) {
                        selectFields.forEach(el => {
                            setRequired(el, selectObject, hiddenFields[h]);
                        });
                    }
                    if (inputFields && inputFields.length > 0) {
                        inputFields.forEach(el => {
                            setRequired(el, selectObject, hiddenFields[h]);
                        });
                    }
                    if (textareaFields && textareaFields.length > 0) {
                        textareaFields.forEach(el => {
                            setRequired(el, selectObject, hiddenFields[h]);
                        });
                    }

                }
            }
        }
    }
};

export default Form;