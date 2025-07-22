const inputCheckbox = document.getElementById('professional_experiences_edit_endDateCheckbox_0');
const endDateMonth = document.getElementById('professional_experiences_edit_endDate_month');
const endDateYear = document.getElementById('professional_experiences_edit_endDate_year');
const endDateDay = document.getElementById('professional_experiences_edit_endDate_day');
const dateEndBlock = document.querySelector('.end-date');

// if value of month and value of year about date of end
// is greater than or equal to 1 put checked on the
// checkbox and show the block about date of end
if (endDateMonth.value >= 1 && endDateYear.value >= 1) {
    inputCheckbox.checked = true;
    dateEndBlock.style.display = 'block';
}
else {
    inputCheckbox.checked = false;
    dateEndBlock.style.display = 'none';
}


inputCheckbox.addEventListener('click', () => {
    const isChecked = inputCheckbox.checked;

    if (isChecked) {
        dateEndBlock.style.display = 'block';
        endDateMonth.required = true;
        endDateYear.required = true;
    } else {
        dateEndBlock.style.display = 'none';
        endDateMonth.required = false;
        endDateYear.required = false;

        // If checkbox about date of end is not checked
        // put null to the values about date of end (Day, Month, Year)
        endDateMonth.value = null;
        endDateYear.value = null;
        endDateDay.value = null;
    }
});