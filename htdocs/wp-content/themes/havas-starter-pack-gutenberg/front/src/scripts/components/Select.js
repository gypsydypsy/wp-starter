'use strict';

const Select = {
    instances: [],
    init: function (reset, choicesItems, selectItem) {
        const selectBoxes = document.querySelectorAll('.js-select');
        if (selectBoxes && selectBoxes.length > 0) {
            selectBoxes.forEach(selectBox => {
                if (reset) {
                    if (selectItem) {
                        if (selectBox.name === selectItem.name) {
                            const instanceEl = Select.instances.filter(el => el.name === selectItem.name);
                            const instance = instanceEl[0].choices;
                            if (instance) {
                                instance.clearChoices();
                                if (choicesItems && choicesItems.length > 0) {
                                    instance.setChoices(choicesItems, 'value', 'label', true)
                                        .setChoiceByValue('');
                                    if (choicesItems.length > 0) {
                                        instance.enable();
                                    }
                                } else {
                                    instance.setChoices(instanceEl[0].options, 'value', 'label', true)
                                        .setChoiceByValue('');
                                    instance.disable();
                                }
                            }
                        }
                    } else {
                        if (Select.instances && Select.instances.length > 0) {
                            Select.instances.forEach(instance => {
                                instance.choices.clearChoices();
                                instance.choices.setChoices(instance.options, 'value', 'label', true)
                                    .setChoiceByValue('');
                                //console.log(instance.options);
                                if (instance.options.length === 1) {
                                    instance.choices.disable();
                                }
                            });
                        }
                    }
                } else {
                    let options = [];
                    for (let i = 0, l = selectBox.childNodes.length; i < l; i++) {
                        if (selectBox.childNodes[i].nodeName === 'OPTION') {
                            options.push({
                                "value": selectBox.childNodes[i].value,
                                "label": selectBox.childNodes[i].innerHTML
                            });
                        }
                    }

                    const searchEnabled = selectBox.dataset.search;
                    const choices = new Choices(selectBox, {
                        searchEnabled: searchEnabled,
                        itemSelectText: "",
                        allowHTML: false,
                        renderSelectedChoices: false,
                        shouldSort: false
                    });
                    Select.instances.push({'choices': choices, 'options': options, 'name': selectBox.name});
                }
            });
        }
    },
};

export default Select;