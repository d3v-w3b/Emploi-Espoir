/**
 * this page is used for saved in DB clicked information.
 *
 * if an element contain a border, it will be flush in DB
 *
 */
const alternanceInput = document.getElementById('main_objectives_mainObjectives_0');
const alternanceLabel = document.querySelector('label[for="main_objectives_mainObjectives_0"]');

const jobInput = document.getElementById('main_objectives_mainObjectives_1');
const jobLabel = document.querySelector('label[for="main_objectives_mainObjectives_1"]');

const btn = document.querySelector('button');

/**
 * this function allow to activate submit btn if an input is checked,
 * and deactivate it when no input is checked
 */
function btnSubmit() {
    //get all inputs
    let allInputs = document.querySelectorAll('input');

    if(allInputs[0].checked || allInputs[1].checked) {
        btn.disabled = false;
    }
    else {
        btn.disabled = true;
    }
}

//alternance input manager
alternanceInput.addEventListener('click', (event) => {

    if(alternanceInput.checked) {
        alternanceLabel.style.borderColor = 'blue';
        btnSubmit();
    }
    else {
        alternanceLabel.style.borderColor = 'grey';
        btnSubmit();
    }
});


//job input manager
jobInput.addEventListener('click', (event) => {

    if(jobInput.checked) {
        jobLabel.style.borderColor = 'blue';
        btnSubmit();

    }
    else {
        jobLabel.style.borderColor = 'grey';
        btnSubmit();
    }
});

