/**
 * This file is use for formation manager and formation edit
 */


let diplomaName = document.querySelector('.diploma-name-input');
let selectForSpeciality = document.querySelector('.diploma-speciality-select-tag');


// Vérifie la valeur au chargement de la page
selectForSpeciality.disabled = diplomaName.value.trim().length === 0;

diplomaName.addEventListener('input', (event) => {
    //console.log(event.target.value.length);
    if(event.target.value.length <= 0 ) {
        selectForSpeciality.disabled = true;
    }
    else {
        selectForSpeciality.disabled = false;
    }
})