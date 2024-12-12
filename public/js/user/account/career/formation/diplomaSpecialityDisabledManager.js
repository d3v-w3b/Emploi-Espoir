let diplomaName = document.getElementById('formation_manager_types_diplomaName');
let selectForSpeciality = document.getElementById('formation_manager_types_diplomaSpeciality');

diplomaName.addEventListener('input', (event) => {

    if(event.target.value === '') {
        selectForSpeciality.disabled = true;
    }
    else {
        selectForSpeciality.disabled = false;
    }
})