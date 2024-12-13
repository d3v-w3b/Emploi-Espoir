let jobTitle = document.getElementById('professional_experiences_manager_jobTitle');
let jobFieldsSelectTag = document.getElementById('professional_experiences_manager_jobField');

jobTitle.addEventListener('input', (event) => {
    if(event.target.value === '') {
        jobFieldsSelectTag.disabled = true;
    }
    else {
        jobFieldsSelectTag.disabled = false;
    }
});


/**
 * This part manage date of end
 *
 * if checkbox of date of end is checked,
 * input date of end is required and display = inline
 *
 * else
 * input date of end is not required and display = none
 */
let checkboxDate = document.getElementById('professional_experiences_manager_endDateCheckbox_0');
let endDate = document.getElementById('professional_experiences_manager_endDate');
let endDateBlock = document.querySelector('.end-date');

checkboxDate.addEventListener('change', (event) => {
    if(checkboxDate.checked) {
        endDateBlock.style.display = 'block';
        endDate.required = true
    }
    else {
        endDateBlock.style.display = 'none';
        endDate.required = false;
    }
});

