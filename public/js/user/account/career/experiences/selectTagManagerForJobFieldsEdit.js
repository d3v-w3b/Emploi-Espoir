$(document).ready(function() {
    $('#professional_experiences_edit_jobField').select2({
        placeholder: "Sélectionnez le ou les domaines du poste",
        allowClear: true
    });
});

let jobTitle = document.getElementById('professional_experiences_edit_jobTitle');
let jobFieldsSelectTag = document.getElementById('professional_experiences_edit_jobField');

// Check the status of the job title on page load
document.addEventListener('DOMContentLoaded', (event) => {
    if (jobTitle.value.length > 0) {
        jobFieldsSelectTag.disabled = false;
    }
    else {
        jobFieldsSelectTag.disabled = true;
    }
});

jobTitle.addEventListener('input', (event) => {
    if (event.target.value.length > 0) {
        jobFieldsSelectTag.disabled = false;
    }
    else {
        jobFieldsSelectTag.disabled = true;
    }
});