const filterForm = document.querySelector('form');

const typeOfContractInputs = document.querySelectorAll('input[name="filter_job_offer[typeOfContract]"]');
const jobFieldsInputs = document.querySelectorAll('input[name="filter_job_offer[organizationField]"]');

typeOfContractInputs.forEach(contractInput => {
    contractInput.addEventListener('input', (event) => {
        if(contractInput.checked) {
            filterForm.submit();
        }
    });
});


jobFieldsInputs.forEach(jobFieldsInput => {
    jobFieldsInput.addEventListener('input', (event) => {
        if(jobFieldsInput.checked) {
            filterForm.submit();
        }
    });
});