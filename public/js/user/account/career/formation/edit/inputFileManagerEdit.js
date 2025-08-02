const inputFile = document.querySelector('.input-file');
const blockForFile = document.querySelector('.inputForFile');
const filePlaceholder = document.querySelector('.new-files');
const removedFileInput = document.querySelector('input[name="formation_edit[removed_files]"]');

const MAX_FILES = 3;
const MAX_FILE_SIZE = 5 * 1024 * 1024;

let selectedFiles = [];
let existingFiles = [];

function updateFilePlaceholder() {
    filePlaceholder.innerHTML = selectedFiles.length > 0
        ? selectedFiles.map(file => `
            <div class="new-file">
                ${file.name} 
                <a href="#" class="remove-file" data-file="${file.name}">retire</a>
            </div>
        `).join('')
        : "";

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
    updateInputFile();
}

function updateInputFile() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    inputFile.files = dataTransfer.files;
}

document.querySelectorAll('.remove-existing-file').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const fileName = e.target.getAttribute('data-file');

        // Retirer l'affichage
        e.target.closest('.existing-file').remove();

        // Marquer pour suppression
        existingFiles.push(fileName);

        // Mise à jour du champ caché
        if (removedFileInput) {
            removedFileInput.value = JSON.stringify(existingFiles);
        }
    });
});


blockForFile.addEventListener('click', () => {
    inputFile.click();
});

inputFile.addEventListener('change', (event) => {
    let filesSelected = Array.from(event.target.files);

    filesSelected.forEach(file => {
        const allowedTypes = ["application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];

        if (!allowedTypes.includes(file.type)) {
            Swal.fire(`Le fichier "${file.name}" n'est pas dans un format autorisé (.pdf, .doc, .docx)`);
            return;
        }

        if (selectedFiles.length >= MAX_FILES) {
            Swal.fire(`Vous ne pouvez sélectionner que ${MAX_FILES} fichiers maximum.`);
            return;
        }

        if (file.size > MAX_FILE_SIZE) {
            Swal.fire(`Le fichier "${file.name}" dépasse la taille maximale de 5 Mo.`);
            return;
        }

        if (!selectedFiles.find(f => f.name === file.name)) {
            selectedFiles.push(file);
        }
    });

    updateFilePlaceholder();
    updateInputFile();
});


