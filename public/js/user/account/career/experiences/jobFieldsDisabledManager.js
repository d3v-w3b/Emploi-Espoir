let jobTitle = document.getElementById('professional_experiences_manager_jobTitle');
let jobFieldsSelectTag = document.getElementById('professional_experiences_manager_jobField');

// Check the status of the job title on page load
document.addEventListener('DOMContentLoaded', (event) => {
    if (jobTitle.value.length > 0) {
        jobFieldsSelectTag.disabled = false;
    } else {
        jobFieldsSelectTag.disabled = true;
    }
});

jobTitle.addEventListener('input', (event) => {
    if (event.target.value.length > 0) {
        jobFieldsSelectTag.disabled = false;
    } else {
        jobFieldsSelectTag.disabled = true;
    }
});


/**
 * This part manage date of end
 *
 * if checkbox of date of end is checked,
 * input date of end is required and display = block
 *
 * else
 * input date of end is not required and display = none
 */
let inputCheckbox = document.getElementById('professional_experiences_manager_endDateCheckbox_0');
let endDateBlock = document.querySelector('.end-date');
let endDateDay = document.getElementById('professional_experiences_manager_endDate_day');
console.log(endDateDay.value);
let endDateMonth = document.getElementById('professional_experiences_manager_endDate_month');
let endDateYear = document.getElementById('professional_experiences_manager_endDate_year');

// Function for manage displaying of date field
function toggleEndDateBlock() {
    if (inputCheckbox.checked) {
        endDateBlock.style.display = 'block';
        endDateMonth.required = true;
        endDateYear.required = true;

        // Initialize the end date day to 1 to avoid validation error
        // in the backend
        endDateDay.value = 1;
    }
    else {
        endDateBlock.style.display = 'none';
        endDateMonth.required = false;
        endDateYear.required = false;

        // If checkbox about date of end is not checked
        // put null to the values about date of end (Day, Month, Year)
        endDateMonth.value = null;
        endDateYear.value = null;
        endDateDay.value = null;
    }
}

// Check on page load
document.addEventListener('DOMContentLoaded', toggleEndDateBlock);

// Check every time the checkbox changes
inputCheckbox.addEventListener('change', toggleEndDateBlock);


/**
 * This part manage words counter about the job description
 */
let jobDescription = document.getElementById('professional_experiences_manager_jobDescription');
let wordCounter = document.querySelector('.words-counter');
const maxLength = 1000;

function updateCounter(count) {
    wordCounter.textContent = `${count}/${maxLength}`;
}

// Mise à jour du compteur au chargement de la page
updateCounter(jobDescription.value.length);

jobDescription.addEventListener('input', (event) => {
    let textLength = event.target.value.length;

    // Empêcher d'écrire plus de 1000 caractères
    if (textLength > maxLength) {
        jobDescription.value = jobDescription.value.substring(0, maxLength);
        textLength = maxLength;
    }

    updateCounter(textLength);
});

