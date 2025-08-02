const selectTag = document.getElementById('external_links_manager_linkType');
const form = document.querySelector('form');

const linkedInInput = document.getElementById('external_links_manager_linkedInUrl');
const linkedInBlock = document.getElementById('linkedIn-block');
const githubInput = document.getElementById('external_links_manager_githubUrl');
const githubBlock = document.getElementById('github-block');
const urlInput = document.getElementById('external_links_manager_websiteUrl');
const urlBlock = document.getElementById('url-block');

// Messages d'erreur
const linkedFormatErrorSpan = document.querySelector('.linkedFormat-error');
const githubFormatErrorSpan = document.querySelector('.githubFormat-error');
const urlFormatErrorSpan = document.querySelector('.urlFormat-error');

// Regex de validation
const linkedFormatRegex = /^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9%_\-]+\/?$/;
const githubFormatRegex = /^https:\/\/github\.com\/[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
const urlFormatRegex = /^https?:\/\/(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})(\/[a-zA-Z0-9._~:\/?#\[\]@!$&'()*+,;=-]*)?$/;

// Empêche la soumission du formulaire si invalide
function preventSubmit(event) {
    event.preventDefault();
}
function forbiddenForm() {
    form.addEventListener('submit', preventSubmit);
}
function allowForm() {
    form.removeEventListener('submit', preventSubmit);
}

// ========== Listeners de validation (ajoutés une seule fois) ==========

linkedInInput.addEventListener('change', (event) => {
    console.log(linkedInInput.value);
    if (selectTag.value === 'LinkedIn') {
        if (!linkedFormatRegex.test(event.target.value)) {
            forbiddenForm();
            linkedFormatErrorSpan.style.display = 'inline';
        } else {
            linkedFormatErrorSpan.style.display = 'none';
            allowForm();
        }
    }
});

githubInput.addEventListener('change', (event) => {
    if (selectTag.value === 'Github') {
        if (!githubFormatRegex.test(event.target.value)) {
            forbiddenForm();
            githubFormatErrorSpan.style.display = 'inline';
        } else {
            githubFormatErrorSpan.style.display = 'none';
            allowForm();
        }
    }
});

urlInput.addEventListener('change', (event) => {
    if (selectTag.value === 'Autre') {
        if (!urlFormatRegex.test(event.target.value)) {
            forbiddenForm();
            urlFormatErrorSpan.style.display = 'inline';
        } else {
            urlFormatErrorSpan.style.display = 'none';
            allowForm();
        }
    }
});

// ========== Gestion de l'affichage dynamique ==========

selectTag.addEventListener('input', (event) => {
    const value = event.target.value;

    // Réinitialisation des champs non sélectionnés
    if (value !== 'LinkedIn') {
        linkedInInput.value = '';
        linkedFormatErrorSpan.style.display = 'none';
    }
    if (value !== 'Github') {
        githubInput.value = '';
        githubFormatErrorSpan.style.display = 'none';
    }
    if (value !== 'Autre') {
        urlInput.value = '';
        urlFormatErrorSpan.style.display = 'none';
    }

    // Affichage conditionnel
    linkedInBlock.style.display = (value === 'LinkedIn') ? 'block' : 'none';
    linkedInInput.required = (value === 'LinkedIn');

    githubBlock.style.display = (value === 'Github') ? 'block' : 'none';
    githubInput.required = (value === 'Github');

    urlBlock.style.display = (value === 'Autre') ? 'block' : 'none';
    urlInput.required = (value === 'Autre');
});
