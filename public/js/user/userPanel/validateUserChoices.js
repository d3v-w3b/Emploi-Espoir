/**
 * this page is used for saved in DB clicked information.
 *
 * if an element contain a border, it will be flush in DB
 *
 */
const allMainObjectivesInput = document.querySelectorAll('input[name="main_objectives[mainObjectives][]"]');
const allFieldsOfInterestInput = document.querySelectorAll('input[name="main_objectives[fields][]"]');

const btn = document.querySelector('button');


/**
 * this function allow to activate submit btn if an input
 * for each element is checked,
 * and deactivate it when no input is checked
 */
function btnSubmit() {
    // Vérifier si au moins un élément est coché dans chaque ensemble
    const isMainObjectivesChecked = Array.from(allMainObjectivesInput).some(input => input.checked);
    const isFieldsOfInterestChecked = Array.from(allFieldsOfInterestInput).some(input => input.checked);

    // Activer le bouton si au moins un checkbox est coché dans chaque catégorie
    btn.disabled = !(isMainObjectivesChecked && isFieldsOfInterestChecked);
}

// Ajouter un événement pour surveiller les changements sur les checkboxes
allMainObjectivesInput.forEach(input => input.addEventListener('change', btnSubmit));
allFieldsOfInterestInput.forEach(input => input.addEventListener('change', btnSubmit));

function updateLabelBorder(inputs) {
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            const label = document.querySelector(`label[for="${input.id}"]`);
            if (input.checked) {
                label.classList.add('selected-label');
            } else {
                label.classList.remove('selected-label');
            }
        });
    });
}

// Appliquer la fonction aux deux ensembles
updateLabelBorder(allMainObjectivesInput);
updateLabelBorder(allFieldsOfInterestInput);
