let diplomaName = document.getElementById('formation_manager_types_diplomaName');
let selectForSpeciality = document.getElementById('formation_manager_types_diplomaSpeciality');


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