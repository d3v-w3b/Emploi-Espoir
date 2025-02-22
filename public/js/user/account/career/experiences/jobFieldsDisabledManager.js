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
let checkboxDate = document.getElementById('professional_experiences_manager_endDateCheckbox_0');
let endDate = document.getElementById('professional_experiences_manager_endDate');
let endDateBlock = document.querySelector('.end-date');

// Function for manage displaying of date field
function toggleEndDateBlock() {
    if (checkboxDate.checked) {
        endDateBlock.style.display = 'block';
        endDate.required = true;
    } else {
        endDateBlock.style.display = 'none';
        endDate.required = false;
    }
}

// Check on page load
document.addEventListener('DOMContentLoaded', toggleEndDateBlock);

// Check every time the checkbox changes
checkboxDate.addEventListener('change', toggleEndDateBlock);

