let selectTag = document.getElementById('formation_manager_types_diplomaLevel');
let diplomaDetails = document.getElementById('diploma-details')

// on page load, hide the #diploma-details block
if(selectTag.value !== 'Aucun diplôme') {
    diplomaDetails.style.display = 'grid';
}

selectTag.addEventListener('change', (event) => {

    if(event.target.value === 'Aucun diplôme') {
        diplomaDetails.style.display = 'none';
    }
    else {
        diplomaDetails.style.display = 'grid';
    }
});