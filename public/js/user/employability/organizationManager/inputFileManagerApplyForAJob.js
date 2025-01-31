const inputForFile = document.querySelector('.inputForFile');
const inputFile = document.getElementById('apply_for_a_job_offer_docsToProvide');
const filePlaceholder = document.querySelector('.files-placeholder');

// const for limited number of file and their size
const MAX_FILES = 3;
const MAX_FILE_SIZE = 5 * 1024 * 1024;

let selectedFiles = [];

inputForFile.addEventListener('click', () => {
    inputFile.click();
});

inputFile.addEventListener('change', (event) => {
    // event.target.files get a FileList
    // user Array for convert this FileList to array
    let filesSelected = Array.from(event.target.files);

    if (filesSelected.length === 0) {
        Swal.fire("Vous devez ajouter au moins un fichier.");
        return;
    }


    filesSelected.forEach(file => {
        if (!file.type === "application/pdf") {
            Swal.fire(`Le fichier "${file.name}" n'est pas un fichier PDF.`);
            return;
        }

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
    filePlaceholder.innerHTML = selectedFiles.length > 0
        ? selectedFiles.map(file => `${file.name} <a href="#" class="remove-file" data-file="${file.name}">retirer</a>`).join('<br>')
        :"<p>Aucun fichier sélectionné.</p>";

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