let inputFile = document.getElementById('formation_manager_types_diploma');
let blockForFile = document.querySelector('.inputForFile');
let filePlaceholder = document.querySelector('.files-placeholder');

// const for limited number of file and their size
const MAX_FILES = 5;
const MAX_FILE_SIZE = 5 * 1024 * 1024;

let selectedFiles = [];

blockForFile.addEventListener('click', (event) => {
    inputFile.click();
});

inputFile.addEventListener('change', (event) => {
    // event.target.files get a FileList
    // user Array for convert this FileList to array
    let filesSelected = Array.from(event.target.files);

    filesSelected.forEach(file => {
        if (selectedFiles.length >= MAX_FILES) {
            //alert(`Vous ne pouvez sélectionner que ${MAX_FILES} fichiers maximum.`);
            Swal.fire(`Vous ne pouvez sélectionner que ${MAX_FILES} fichiers maximum.`);
            return;
        }

        if (file.size > MAX_FILE_SIZE) {
            //alert(`Le fichier "${file.name}" dépasse la taille maximale de 5 Mo.`);
            Swal.fire(`Le fichier "${file.name}" dépasse la taille maximale de 5 Mo.`);
            return;
        }

        if (!selectedFiles.find(f => f.name === file.name)) {
            selectedFiles.push(file);
        }
    });

    updateFilePlaceholder();
});



function updateFilePlaceholder() {
    filePlaceholder.innerHTML = selectedFiles
        .map(file => `${file.name} <a href="#" class="remove-file" data-file="${file.name}">retirer</a>`)
        .join('<br>');

    // Gestion de la suppression des fichiers
    document.querySelectorAll('.remove-file').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const fileName = e.target.getAttribute('data-file');
            removeFile(fileName);
        });
    });
}

function removeFile(fileName) {
    selectedFiles = selectedFiles.filter(file => file.name !== fileName);
    updateFilePlaceholder();
}