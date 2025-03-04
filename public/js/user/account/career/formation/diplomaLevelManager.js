let selectTag = document.getElementById('formation_manager_types_diplomaLevel');
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