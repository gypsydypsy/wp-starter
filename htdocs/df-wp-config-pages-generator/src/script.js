'use strict';

/* Script for step 2 : construisez vos pages */

document.addEventListener('DOMContentLoaded', () => {
    
    const grid = document.querySelector('#blocPageConstruct');

    // ----- Init

    if (grid){

        //Add add row event
        const addRowBtn = document.querySelector('#addRowBtn');
        const flexibles = JSON.parse(grid.dataset.elements);   

        addRowBtn.addEventListener('click', () => addRow(grid, flexibles))


        const rows = document.querySelectorAll('#blocPageConstruct > .page-row');
        
        rows.forEach( row => {

            const hiddenInput = row.querySelector('.hidden-input');
            const selects = row.querySelectorAll('.add-flexible-btn');
            const list = row.querySelector('.flexible-list');
            const deleteRowBtn = row.querySelector('.delete-row-btn');
            const homeRadios = row.querySelectorAll('.home-radio');
            const privacyRadios = row.querySelectorAll('.privacy-radio');

            //Add delete row event
            deleteRowBtn.addEventListener('click', () => {
                deleteRow(row);
                updateIDS(grid);
            });

            //Add add flexible event
            selects.forEach(sel => {
                sel.addEventListener('click', (e) => {
                    addFlexible(e, list, hiddenInput);
                })
            })

            //Add radio event
            homeRadios.forEach(rad => {
                rad.addEventListener('click', toggleRadios)
            })

            privacyRadios.forEach(rad => {
                rad.addEventListener('click', togglePrivacyRadios)
            })
        })
    }

    // ----- Funcs

    function addRow (parentElement, flexibles){

        //Builds the select element
        const select = document.createElement('ul');
        select.classList.add('form-select');
        select.classList.add('flexible-select');
        flexibles.forEach(elt => {
            const li = document.createElement('li');
            li.innerHTML = `
                ${elt.label} <i data-value="${elt.name}" data-label="${elt.label}" class="bi bi-plus-circle add-flexible-btn"></i>
            `;
            select.appendChild(li)
        })

        //Builds the new row
        const newRow = document.createElement('div');
        newRow.classList.add("page-row");
        
        newRow.innerHTML = `
            <div class="row-heading">
                <h3 class="form-label">Page n°<span class="nth-row">1</span></h3>
                <span class="delete-row-btn">
                    <i class="bi bi-trash"></i>
                </span>
            </div>
            <div class="form-group">
                <label for="page_titles" class="form-label">Titre de la page <span>*</span></label>
                <input type="text" name="page_titles[]" class="form-control" required="required" id="page_titles[]" placeholder="Titre">
            </div>
            <div class="form-group">
                <label for="page_home[]">Est-ce la page d'accueil ?</label>
                <div class="check-group">
                    <input class="home-radio home-radio-true" type="radio" name="page_home[]" id="page_home_true[]" value="true">
                    <label for="page_home_true[]">oui</label>
                    <input class="home-radio home-radio-false" type="radio" checked name="page_home[]" id="page_home_false[]" value="false">
                    <label for="page_home_false[]">non</label>
                </div>
            </div>
            <div class="form-group">
                <label for="page_privacy[]">Est-ce la page de confidentialité ?</label>
                <div class="check-group">
                    <input class="privacy-radio privacy-radio-true" type="radio" name="page_privacy[]" id="page_privacy_true[]" value="true">
                    <label for="page_privacy_true[]">oui</label>
                    <input class="privacy-radio privacy-radio-false" type="radio" checked name="page_privacy[]" id="page_privacy_false[]" value="false">
                    <label for="page_privacy_false[]">non</label>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleSelect2" class="form-label">Sélectionnez des modules</label>
                <p>Conformément à l'agencement prévu en sélectionnant parmi la liste un même module autant de fois que nécessaire</p>
                <div class="flexible-group">
                    ${select.outerHTML}
                    <ul class="flexible-list"></ul>
                </div>
            </div>
            <input name="flexibles_selection[]" type="hidden" class='hidden-input'/>
        `
    
        parentElement.appendChild(newRow);
    
        // Add delete row event
        const deleteRowBtn = newRow.querySelector('.delete-row-btn');
        deleteRowBtn.addEventListener('click', () => {
            deleteRow(newRow);
            updateIDS(parentElement);
        });

        //Add add flexible event
        const addFlexibleBtns = newRow.querySelectorAll('.add-flexible-btn');

        addFlexibleBtns.forEach(btn => {
            btn.addEventListener('click', (e) => addFlexible(e, newRow.querySelector('.flexible-list'), newRow.querySelector('.hidden-input')));
        })
    
        // Add radio event
        const homeRadios = newRow.querySelectorAll('.home-radio');

        homeRadios.forEach(rad => {
            rad.addEventListener('click', toggleRadios)
        })

        const privacyRadios = newRow.querySelectorAll('.privacy-radio');

        privacyRadios.forEach(rad => {
            rad.addEventListener('click', togglePrivacyRadios)
        })

        //Update nth rows
        updateIDS(parentElement);
    }
    
    function deleteRow(row) {
        if(grid.children.length > 1){
            row.remove();
        }
    }

    function addFlexible(e, list, input){
        const val = e.target.dataset.value;
        const name = e.target.dataset.label;
        
        const deleteFlexibleBtn = document.createElement('span');
        deleteFlexibleBtn.innerHTML = '<i class="bi bi-trash"></i>'

        const li = document.createElement('li');
        li.innerHTML = name;
        li.dataset.value = val;
        li.appendChild(deleteFlexibleBtn);
        list.appendChild(li);

        // Add events on delete btn
        deleteFlexibleBtn.addEventListener('click', () => {
            deleteFlexible(li);
            updateHiddenInputValue(input, list);
        });

        //Update input value 
        updateHiddenInputValue(input, list);
        
    }

    function deleteFlexible(element){
        element.remove();
    }

    function updateHiddenInputValue (input, list){
        let inputValue = [];
        for (let li of list.children){
            inputValue.push(li.dataset.value);
        }
        
        inputValue = JSON.stringify(inputValue)
        input.value = inputValue;
    }

    function updateIDS (parentElement) {
        const rows = parentElement.children;

        Array.from(rows).forEach( (row, rowIndex) => {
            const nthRows = row.querySelector('.nth-row');
            const radios = row.querySelectorAll(".home-radio")
            const radiosPrivacy = row.querySelectorAll(".privacy-radio")
            nthRows.textContent = rowIndex + 1;
            
            radios.forEach(radio => {
                radio.name=`page_home[${rowIndex}]`;
                radio.id = radio.classList.contains("home-radio-true") ? `page_home_true[${rowIndex}]` : `page_home_false[${rowIndex}]`;
                radio.nextElementSibling.setAttribute('for', radio.classList.contains("home-radio-true") ? `page_home_true[${rowIndex}]` : `page_home_false[${rowIndex}]` );
            })

            radiosPrivacy.forEach(radio => {
                radio.name=`page_privacy[${rowIndex}]`;
                radio.id = radio.classList.contains("privacy-radio-true") ? `page_privacy_true[${rowIndex}]` : `page_privacy_false[${rowIndex}]`;
                radio.nextElementSibling.setAttribute('for', radio.classList.contains("privacy-radio-true") ? `page_privacy_true[${rowIndex}]` : `page_privacy_false[${rowIndex}]` );
            })
        })
    }

    function toggleRadios(e){
        const radios = document.querySelectorAll('.home-radio')
        let selectedName;

        if (e.target.classList.contains("home-radio-true")){
            selectedName = e.target.name;
        }
        
        if(selectedName){
            radios.forEach((radio) => {
                if (radio.name !== selectedName){
                    if(radio.classList.contains('home-radio-false')) radio.checked = true;
                }
            })
        }
    }

    function togglePrivacyRadios(e){
        const privacyRadios = document.querySelectorAll('.privacy-radio')
        let selectedName;

        if (e.target.classList.contains("privacy-radio-true")){
            selectedName = e.target.name;
        }

        if(selectedName){
            privacyRadios.forEach((radio) => {
                if (radio.name !== selectedName){
                    if(radio.classList.contains('privacy-radio-false')) radio.checked = true;
                }
            })
        }
    }
})



