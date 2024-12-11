let diplomaName = document.getElementById('formation_manager_types_diplomaName');
let selectForSpecialities = document.getElementById('formation_manager_types_diplomaSpecialities');

diplomaName.addEventListener('input', (event) => {

    if(event.target.value === '') {
        selectForSpecialities.disabled = true;
    }
    else {
        selectForSpecialities.disabled = false;
    }
})