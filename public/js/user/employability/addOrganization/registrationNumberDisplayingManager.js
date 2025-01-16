const inputYes = document.getElementById('organization_basics_infos_registrationNumberChoice_0');
const inputNo = document.getElementById('organization_basics_infos_registrationNumberChoice_1');
const form = document.querySelector('form');

const blockRegistrationNumber = document.getElementById('registrationNumberInputBlock');
const inputRegistrationNumber = document.getElementById('organization_basics_infos_organizationRegistrationNumber');

// Fonction pour réinitialiser un champ
function resetInput(input) {
    input.value = ''; // Efface la valeur
}

if (inputYes.checked) {
    blockRegistrationNumber.style.display = 'block';
    inputRegistrationNumber.required = true;
} else {
    blockRegistrationNumber.style.display = 'none';
    inputRegistrationNumber.required = false;
}

inputYes.addEventListener('change', () => {
    blockRegistrationNumber.style.display = 'block';
    inputRegistrationNumber.required = true;
});

inputNo.addEventListener('change', () => {
    blockRegistrationNumber.style.display = 'none';
    inputRegistrationNumber.required = false;
    resetInput(inputRegistrationNumber);
});