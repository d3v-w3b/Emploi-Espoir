const deletionLink = document.querySelector('.deletion-link');
const modalForForm = document.getElementById('form-delete-account');
const backdrop = document.getElementById('modal-backdrop');
const ulTagDuringError = document.querySelector('ul');

deletionLink.addEventListener('click', (event) => {
    event.preventDefault();

    // Show modal and backdrop
    modalForForm.style.display = 'block';
    backdrop.style.display = 'block';

});

if (ulTagDuringError) {
    // Show modal and backdrop
    modalForForm.style.display = 'block';
    backdrop.style.display = 'block';
}


// Close modal when clicking on the backdrop
backdrop.addEventListener('click', () => {
    modalForForm.style.display = 'none';
    backdrop.style.display = 'none';
});
