/**
 * This file is use for the diploma level into formation manager and formation edit
 */

let selectTag = document.querySelector('.diploma-level');
let diplomaDetails = document.getElementById('diploma-details')

// on page load, hide the #diploma-details block
diplomaDetails.style.display = selectTag.value === 'Aucun diplôme' ? 'none' : 'grid';


selectTag.addEventListener('change', (event) => {

    if(event.target.value === 'Aucun diplôme') {
        diplomaDetails.style.display = 'none';
    }
    else {
        diplomaDetails.style.display = 'grid';
    }
});