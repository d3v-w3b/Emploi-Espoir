/**
 * This file is use for formation manager and formation edit
 */


let diplomaName = document.querySelector('.diploma-name-input');
const selectTagForDiplomaLevel = document.querySelector('.diploma-level');
let selectForSpeciality = document.querySelector('.diploma-speciality-select-tag');


// Fonction centrale pour activer/désactiver le select
function updateSpecialityState() {
    const diplomaNameHasValue = diplomaName.value.trim().length > 0;
    const isBacLevel = selectTagForDiplomaLevel.value === 'Bac';

    if (diplomaNameHasValue && !isBacLevel) {
        selectForSpeciality.disabled = false;
        selectForSpeciality.required = true;
    } else {
        selectForSpeciality.disabled = true;
        selectForSpeciality.required = false;
        selectForSpeciality.value = null;
    }
}

// Appel initial au chargement de la page
updateSpecialityState();

// Ajout des écouteurs d'événement
diplomaName.addEventListener('input', updateSpecialityState);
selectTagForDiplomaLevel.addEventListener('change', updateSpecialityState);
