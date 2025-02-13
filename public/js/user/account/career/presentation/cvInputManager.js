const inputForFile = document.querySelector('.inputForFile');
const inputFile = document.getElementById('cv_manager_cv');
const filePlaceholder = document.querySelector('.files-placeholder');

// const for limited number of file and their size
const MAX_FILE_SIZE = 5 * 1024 * 1024;

let selectedFile = null;

function updateFilePlaceholder() {
    if (selectedFile) {
        filePlaceholder.innerHTML = `${selectedFile.name} <a href="#" class="remove-file" data-file="${selectedFile.name}">retirer</a>`;
    } else {
        filePlaceholder.innerHTML = "";
    }
    // Gestion de la suppression des fichiers
    document.querySelectorAll('.remove-file').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            removeFile();
        });
    });
}


function removeFile() {
    selectedFile = null;
    updateFilePlaceholder();
}



function updateInputFile() {
    const dataTransfer = new DataTransfer();
    if (selectedFile) dataTransfer.items.add(selectedFile);
    inputFile.files = dataTransfer.files;
}


document.querySelector('form').addEventListener('submit', (event) => {
    if (!selectedFile) {
        event.preventDefault(); // Empêche la soumission du formulaire
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Vous devez ajouter au moins un fichier pour soumettre le formulaire.'
        });
    } else {
        // Mettre à jour le champ input file avant soumission
        updateInputFile();
    }
});


inputForFile.addEventListener('click', () => {
    inputFile.click();
});

inputFile.addEventListener('change', (event) => {

    // Get the current file selected
    let filesSelected = event.target.files[0];

    if (!filesSelected) {
        Swal.fire("Vous devez ajouter au moins un fichier.");
        return;
    }


    if (!["application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"].includes(filesSelected.type)) {
        Swal.fire(`Sélectionnez un fichier au format .pdf, .doc ou .docx`);
        return;
    }


    if (filesSelected.size > MAX_FILE_SIZE) {
        Swal.fire(`Le fichier "${filesSelected.name}" dépasse la taille maximale de 5 Mo.`);
        return;
    }

    selectedFile = filesSelected;

    console.log("Fichier sélectionné :", selectedFile);
    console.log("Placeholder avant mise à jour :", filePlaceholder);

    updateFilePlaceholder();
});
